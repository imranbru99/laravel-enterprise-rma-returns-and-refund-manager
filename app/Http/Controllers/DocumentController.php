<?php

namespace App\Http\Controllers;

use App\Models\CreditNote;
use App\Models\Rma;
use App\Services\PdfDocumentService;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    public function __construct(protected PdfDocumentService $pdfService) {}

    public function shippingLabel(Rma $rma): Response
    {
        return $this->pdfService->generateShippingLabel($rma);
    }

    public function rmaVoucher(Rma $rma): Response
    {
        return $this->pdfService->generateRmaVoucher($rma);
    }

    public function creditNote(CreditNote $creditNote, string $locale = 'en'): Response
    {
        return $this->pdfService->generateCreditNotePdf($creditNote, $locale);
    }
}
