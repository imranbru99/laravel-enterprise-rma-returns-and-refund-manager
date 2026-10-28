<?php

namespace App\Filament\Pages;

use App\Models\Rma;
use App\Services\AiAssistantService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class AiAssistantPage extends Page
{
    protected static string|\UnitEnum|null $navigationGroup = 'AI & Automation';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected string $view = 'filament.pages.ai-assistant';

    protected static ?string $title = 'AI Returns & Fraud Intelligence Studio';

    protected static ?int $navigationSort = 1;

    public string $testReason = 'Screen flickering and battery drains in 30 minutes';

    public string $testNotes = 'Received 2 days ago, opened and tested. Item gets very hot.';

    public string $testCategory = 'Electronics';

    public ?array $classificationResult = null;

    public ?int $selectedRmaId = null;

    public ?array $fraudRiskResult = null;

    public string $customerQuery = 'How long do I have to exchange a defective tablet for a different model?';

    public ?string $aiReply = null;

    public function mount(): void
    {
        $firstRma = Rma::first();
        if ($firstRma) {
            $this->selectedRmaId = $firstRma->id;
        }
    }

    public function runClassification(AiAssistantService $aiService): void
    {
        $this->classificationResult = $aiService->classifyReturn(
            $this->testReason,
            $this->testNotes,
            $this->testCategory
        );

        Notification::make()
            ->title('AI Classification Completed')
            ->success()
            ->send();
    }

    public function runFraudScoring(AiAssistantService $aiService): void
    {
        if (! $this->selectedRmaId) {
            Notification::make()->title('Please select an RMA')->warning()->send();

            return;
        }

        $rma = Rma::find($this->selectedRmaId);
        if (! $rma) {
            return;
        }

        $this->fraudRiskResult = $aiService->assessFraudRisk($rma);

        Notification::make()
            ->title('Fraud Risk Evaluation Complete')
            ->info()
            ->send();
    }

    public function runCustomerSupport(AiAssistantService $aiService): void
    {
        $this->aiReply = $aiService->askAssistant($this->customerQuery);

        Notification::make()
            ->title('AI Answer Generated')
            ->success()
            ->send();
    }
}
