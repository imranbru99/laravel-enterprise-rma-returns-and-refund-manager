<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ in_array($locale, ['ar', 'ur', 'he']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $locale === 'bn' ? 'ক্রেডিট নোট' : ($locale === 'ar' ? 'إشعار دائن' : 'Official Credit Note') }}</title>
    <style>
        body { font-family: 'SolaimanLipi', 'Noto Sans Bengali', 'Amiri', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin: 0; padding: 24px; color: #1f2937; line-height: 1.6; }
        .invoice-card { max-width: 700px; margin: auto; border: 1px solid #d1d5db; border-radius: 8px; padding: 32px; background: #fff; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #374151; padding-bottom: 20px; margin-bottom: 24px; }
        .title { font-size: 24px; font-weight: 800; color: #111827; }
        .number { font-size: 16px; font-weight: 700; color: #4b5563; }
        .grid { display: flex; justify-content: space-between; margin-bottom: 24px; font-size: 13px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .table th { background: #f3f4f6; padding: 10px; border-bottom: 2px solid #d1d5db; text-align: left; }
        .table td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        .total-box { text-align: right; font-size: 15px; margin-top: 12px; }
        .grand-total { font-size: 18px; font-weight: 800; color: #111827; }
    </style>
</head>
<body>
    <div class="invoice-card">
        <div class="header">
            <div>
                <div class="title">
                    @if($locale === 'bn')
                        অফিসিয়াল ক্রেডিট নোট
                    @elseif($locale === 'ar')
                        إشعار دائن رسمي
                    @else
                        OFFICIAL CREDIT NOTE
                    @endif
                </div>
                <div class="number">{{ $creditNote->credit_note_number }}</div>
            </div>
            <div style="text-align: right; font-size: 13px;">
                <strong>{{ $locale === 'bn' ? 'তারিখ' : ($locale === 'ar' ? 'التاريخ' : 'Date') }}:</strong> {{ $creditNote->issued_at?->format('M d, Y') ?? date('M d, Y') }}<br>
                <strong>{{ $locale === 'bn' ? 'স্ট্যাটাস' : ($locale === 'ar' ? 'الحالة' : 'Status') }}:</strong> <span style="text-transform: uppercase; font-weight: bold; color: #059669;">{{ $creditNote->status }}</span>
            </div>
        </div>

        <div class="grid">
            <div>
                <strong>{{ $locale === 'bn' ? 'গ্রাহকের বিবরণ' : ($locale === 'ar' ? 'بيانات العميل' : 'Issued To') }}:</strong><br>
                {{ $creditNote->customer->name ?? 'Customer' }}<br>
                {{ $creditNote->customer->email }}
            </div>
            <div style="text-align: right;">
                <strong>{{ $locale === 'bn' ? 'রিটার্ন রেফারেন্স' : ($locale === 'ar' ? 'مرجع الإرجاع' : 'RMA Reference') }}:</strong><br>
                {{ $creditNote->rma->rma_number ?? 'N/A' }}<br>
                Branch: {{ $creditNote->rma->branch->name ?? 'Headquarters' }}
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>{{ $locale === 'bn' ? 'বিবরণ' : ($locale === 'ar' ? 'الوصف' : 'Description') }}</th>
                    <th style="text-align: right;">{{ $locale === 'bn' ? 'ট্যাক্স' : ($locale === 'ar' ? 'الضريبة' : 'Tax') }}</th>
                    <th style="text-align: right;">{{ $locale === 'bn' ? 'ক্রেডিট পরিমাণ' : ($locale === 'ar' ? 'المبلغ الدائن' : 'Credit Amount') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{ $locale === 'bn' ? 'অনুমোদিত পণ্য ফেরত এবং রিফান্ড ক্রেডিট' : ($locale === 'ar' ? 'رصيد مسترد لمرتجع معتمد' : 'Approved Merchandise Return Credit') }}
                        (RMA #{{ $creditNote->rma->rma_number ?? 'N/A' }})
                    </td>
                    <td style="text-align: right;">${{ number_format($creditNote->tax_amount, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($creditNote->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-box">
            <div class="grand-total">
                {{ $locale === 'bn' ? 'মোট ব্যালেন্স' : ($locale === 'ar' ? 'إجمالي الرصيد' : 'Total Credit Available') }}:
                ${{ number_format($creditNote->balance_remaining, 2) }} USD
            </div>
        </div>
    </div>
</body>
</html>
