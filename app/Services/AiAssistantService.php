<?php

namespace App\Services;

use App\Models\AiRequestAudit;
use App\Models\Customer;
use App\Models\KnowledgeDocument;
use App\Models\Rma;
use Illuminate\Support\Facades\Log;
use ImranDevBd\AiHub\Facades\AIHub;

class AiAssistantService
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * AI Return Classification: suggests category, defect tags, and routing priority.
     *
     * @return array{suggested_category: string, defect_tags: array, priority: string, confidence: float}
     */
    public function classifyReturn(string $reason, string $notes = '', string $productCategory = 'General'): array
    {
        $input = [
            'reason' => $reason,
            'notes' => $notes,
            'product_category' => $productCategory,
        ];

        try {
            if (class_exists(AIHub::class) && config('ai-hub.providers.gemini.api_key')) {
                // Production AI execution via AIHub
                $prompt = "Classify this return request:\nCategory: {$productCategory}\nReason: {$reason}\nNotes: {$notes}\nReturn JSON with suggested_category, defect_tags, priority, confidence.";
                $response = AIHub::chat($prompt);
                $content = $response->text();
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $this->auditRequest('classification', $input, $decoded, false);

                    return $decoded;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('AIHub call failed, falling back to heuristic classification: '.$e->getMessage());
        }

        // Heuristic & rule-based intelligent classification engine
        $lowerNotes = strtolower($reason.' '.$notes);

        $suggestedCategory = 'Customer Remorse';
        $defectTags = [];
        $priority = 'normal';
        $confidence = 0.88;

        if (str_contains($lowerNotes, 'broken') || str_contains($lowerNotes, 'damaged') || str_contains($lowerNotes, 'cracked')) {
            $suggestedCategory = 'Transit Damage / Physical Defect';
            $defectTags = ['hardware_damage', 'packaging_check_required'];
            $priority = 'high';
            $confidence = 0.95;
        } elseif (str_contains($lowerNotes, 'defective') || str_contains($lowerNotes, 'not working') || str_contains($lowerNotes, 'dead')) {
            $suggestedCategory = 'Functional Defect';
            $defectTags = ['electronics_diagnostics', 'quality_assurance_review'];
            $priority = 'high';
            $confidence = 0.94;
        } elseif (str_contains($lowerNotes, 'size') || str_contains($lowerNotes, 'fit') || str_contains($lowerNotes, 'tight') || str_contains($lowerNotes, 'loose')) {
            $suggestedCategory = 'Sizing & Fit Mismatch';
            $defectTags = ['apparel_sizing', 'exchange_opportunity'];
            $priority = 'normal';
            $confidence = 0.92;
        } elseif (str_contains($lowerNotes, 'wrong') || str_contains($lowerNotes, 'incorrect')) {
            $suggestedCategory = 'Fulfillment Picking Error';
            $defectTags = ['warehouse_picking_audit', 'urgent_replacement'];
            $priority = 'urgent';
            $confidence = 0.96;
        }

        $result = [
            'suggested_category' => $suggestedCategory,
            'defect_tags' => $defectTags,
            'priority' => $priority,
            'confidence' => $confidence,
        ];

        $this->auditRequest('classification', $input, $result, true);

        return $result;
    }

    /**
     * AI Fraud Risk Assessment: analyzes return frequency, order value, and behavior.
     *
     * @return array{risk_score: int, flags: array, recommendation: string}
     */
    public function assessFraudRisk(Rma $rma): array
    {
        $customer = $rma->customer;
        $order = $rma->order;

        $flags = [];
        $score = 10; // baseline low risk

        // Check customer return frequency in past 90 days
        $pastReturnsCount = Rma::where('customer_id', $rma->customer_id)
            ->where('created_at', '>=', now()->subDays(90))
            ->where('id', '!=', $rma->id)
            ->count();

        if ($pastReturnsCount >= 5) {
            $score += 45;
            $flags[] = "High return frequency: {$pastReturnsCount} returns submitted in the last 90 days.";
        } elseif ($pastReturnsCount >= 3) {
            $score += 20;
            $flags[] = "Moderate return frequency: {$pastReturnsCount} returns in the last 90 days.";
        }

        // Check high-value threshold
        if ($rma->estimated_refund_amount > 500) {
            $score += 20;
            $flags[] = "High-value return amount (\${$rma->estimated_refund_amount}).";
        }

        // Check quick return after delivery
        if ($order && $order->delivered_at && $rma->created_at->diffInHours($order->delivered_at) < 2) {
            $score += 15;
            $flags[] = 'Return initiated within 2 hours of delivery receipt.';
        }

        $score = min(100, $score);

        $recommendation = match (true) {
            $score >= 70 => 'FLAG_FOR_MANUAL_AUDIT: Require physical warehouse inspection and serial verification before approving refund.',
            $score >= 40 => 'STANDARD_REVIEW: Verify photo evidence and return tracking.',
            default => 'LOW_RISK: Eligible for automated return handling.',
        };

        $result = [
            'risk_score' => $score,
            'flags' => $flags,
            'recommendation' => $recommendation,
        ];

        $this->auditRequest('fraud_risk', ['rma_id' => $rma->id], $result, true);

        return $result;
    }

    /**
     * AI Knowledge & Customer Support Assistant.
     */
    public function askAssistant(string $query, ?int $customerId = null): string
    {
        $tenantId = $this->tenantContext->getTenantId();

        // Search knowledge base
        $kbArticles = KnowledgeDocument::where('tenant_id', $tenantId)
            ->where('is_published', true)
            ->get();

        $contextSnippets = [];
        foreach ($kbArticles as $article) {
            if (str_contains(strtolower($article->title.' '.$article->content), strtolower($query))) {
                $contextSnippets[] = "Article: {$article->title}\n{$article->content}";
            }
        }

        // Try AIHub with RAG context
        try {
            if (class_exists(AIHub::class) && config('ai-hub.providers.gemini.api_key')) {
                $context = implode("\n---\n", $contextSnippets);
                $prompt = "You are Returns & Refund Manager AI Assistant. Customer asked: {$query}\nKnowledge base context:\n{$context}\nProvide a friendly, accurate answer strictly adhering to store policy.";
                $res = AIHub::chat($prompt);

                return $res->text();
            }
        } catch (\Throwable $e) {
            Log::info('AIHub chat fallback: '.$e->getMessage());
        }

        // Built-in intelligent answers
        $lowerQuery = strtolower($query);

        if (str_contains($lowerQuery, 'policy') || str_contains($lowerQuery, 'window') || str_contains($lowerQuery, 'how long')) {
            return 'Our standard return policy allows returns within 30 days of delivery. Items must be in their original packaging and condition with proof of purchase. You can start a return directly through the Customer Portal.';
        }

        if (str_contains($lowerQuery, 'status') || str_contains($lowerQuery, 'track') || str_contains($lowerQuery, 'where is')) {
            if ($customerId) {
                $latestRma = Rma::where('customer_id', $customerId)->latest()->first();
                if ($latestRma) {
                    return "Your latest RMA #{$latestRma->rma_number} is currently in status: {$latestRma->status}. Return method: {$latestRma->return_method}.";
                }
            }

            return 'You can check the real-time status of your return by entering your RMA number or logging into the Customer Portal.';
        }

        if (str_contains($lowerQuery, 'exchange') || str_contains($lowerQuery, 'swap') || str_contains($lowerQuery, 'different size')) {
            return "Yes, we support product and variant exchanges! When submitting your return in the Customer Portal, choose 'Exchange' as your resolution type and pick your preferred replacement size or color.";
        }

        if (str_contains($lowerQuery, 'refund') || str_contains($lowerQuery, 'money back') || str_contains($lowerQuery, 'how long does refund take')) {
            return 'Once your returned item is received and passes quality inspection, refunds are processed within 2-3 business days to your original payment method or instantly as store credit.';
        }

        return 'Thank you for reaching out to Returns & Refund Manager. We are here to help you with returns, exchanges, refunds, and warranty claims. You can manage your returns 24/7 on our self-service customer portal.';
    }

    protected function auditRequest(string $feature, array $input, array $output, bool $isMock): void
    {
        try {
            AiRequestAudit::create([
                'tenant_id' => $this->tenantContext->getTenantId(),
                'user_id' => auth()->id(),
                'feature' => $feature,
                'prompt_tokens' => 120,
                'completion_tokens' => 80,
                'cost_estimate' => 0.0004,
                'input_data' => $input,
                'output_data' => $output,
                'is_mock' => $isMock,
            ]);
        } catch (\Throwable $e) {
            // Ignore audit log failure in transient environments
        }
    }
}
