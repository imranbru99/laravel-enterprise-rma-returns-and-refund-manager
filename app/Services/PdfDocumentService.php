<?php

namespace App\Services;

use App\Models\CreditNote;
use App\Models\Rma;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use ImranDev\UnicodePdf\Facades\UnicodePdf;
use Symfony\Component\HttpFoundation\Response;

class PdfDocumentService
{
    /**
     * Generate return shipping label document.
     */
    public function generateShippingLabel(Rma $rma): Response
    {
        $data = [
            'rma' => $rma,
            'customer' => $rma->customer,
            'branch' => $rma->destinationBranch ?? $rma->branch,
            'carrier' => $rma->carrier_code ?? 'EXPRESS-RETURNS',
            'trackingNumber' => $rma->tracking_number ?? 'TRK-'.strtoupper(bin2hex(random_bytes(5))),
        ];

        try {
            if (class_exists(UnicodePdf::class)) {
                return UnicodePdf::loadView('documents.shipping-label', $data)
                    ->title("Return-Label-{$rma->rma_number}")
                    ->download("Return-Label-{$rma->rma_number}.pdf");
            }
        } catch (\Throwable $e) {
            Log::warning('UnicodePdf generation failed, falling back to html response: '.$e->getMessage());
        }

        return response(View::make('documents.shipping-label', $data)->render(), 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    /**
     * Generate return authorization voucher / drop-off slip.
     */
    public function generateRmaVoucher(Rma $rma): Response
    {
        $data = [
            'rma' => $rma->load(['items.product', 'customer', 'branch']),
        ];

        try {
            if (class_exists(UnicodePdf::class)) {
                return UnicodePdf::loadView('documents.rma-voucher', $data)
                    ->title("RMA-Voucher-{$rma->rma_number}")
                    ->download("RMA-Voucher-{$rma->rma_number}.pdf");
            }
        } catch (\Throwable $e) {
            Log::warning('UnicodePdf voucher failed: '.$e->getMessage());
        }

        return response(View::make('documents.rma-voucher', $data)->render(), 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    /**
     * Generate multilingual Credit Note / Refund Receipt.
     */
    public function generateCreditNotePdf(CreditNote $creditNote, string $locale = 'en'): Response
    {
        $data = [
            'creditNote' => $creditNote->load(['customer', 'rma.branch']),
            'locale' => $locale,
        ];

        try {
            if (class_exists(UnicodePdf::class)) {
                $doc = UnicodePdf::loadView('documents.credit-note', $data)
                    ->title("Credit-Note-{$creditNote->credit_note_number}")
                    ->locale($locale);

                if (in_array($locale, ['ar', 'ur', 'he'])) {
                    $doc->direction('rtl');
                }

                return $doc->download("Credit-Note-{$creditNote->credit_note_number}.pdf");
            }
        } catch (\Throwable $e) {
            Log::warning('UnicodePdf credit note failed: '.$e->getMessage());
        }

        return response(View::make('documents.credit-note', $data)->render(), 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }
}
