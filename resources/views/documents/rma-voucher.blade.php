<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Return Merchandise Authorization (RMA) Voucher</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 24px; color: #1f2937; line-height: 1.5; }
        .voucher { max-width: 700px; margin: auto; border: 1px solid #e5e7eb; border-radius: 8px; padding: 32px; background: #fff; }
        .top { display: flex; justify-content: space-between; border-bottom: 2px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 24px; }
        .title { font-size: 22px; font-weight: 800; color: #111827; }
        .rma-id { font-size: 16px; font-weight: 700; color: #4f46e5; margin-top: 4px; }
        .meta-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 24px; font-size: 13px; }
        .meta-item strong { display: block; color: #6b7280; font-size: 11px; text-transform: uppercase; margin-bottom: 2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; font-size: 13px; }
        th { background: #f9fafb; text-align: left; padding: 10px; border-bottom: 2px solid #e5e7eb; font-weight: 600; color: #374151; }
        td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        .summary { text-align: right; margin-bottom: 24px; font-size: 14px; }
        .total-row { font-size: 16px; font-weight: bold; color: #111827; }
        .footer { font-size: 11px; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 16px; }
    </style>
</head>
<body>
    <div class="voucher">
        <div class="top">
            <div>
                <div class="title">RETURN AUTHORIZATION VOUCHER</div>
                <div class="rma-id">RMA #{{ $rma->rma_number }}</div>
            </div>
            <div style="text-align: right; font-size: 13px;">
                <strong>Date:</strong> {{ $rma->created_at->format('M d, Y') }}<br>
                <strong>Status:</strong> <span style="text-transform: uppercase; font-weight: bold; color: #059669;">{{ $rma->status }}</span>
            </div>
        </div>

        <div class="meta-grid">
            <div class="meta-item">
                <strong>Customer Information</strong>
                {{ $rma->customer->name ?? 'Valued Customer' }}<br>
                {{ $rma->customer->email }}
            </div>
            <div class="meta-item">
                <strong>Original Order Reference</strong>
                Order #{{ $rma->order->order_number ?? 'N/A' }}<br>
                Channel: {{ ucfirst($rma->order->channel ?? 'Online') }}
            </div>
            <div class="meta-item">
                <strong>Return Method</strong>
                {{ ucwords(str_replace('_', ' ', $rma->return_method)) }}<br>
                Assigned Branch: {{ $rma->branch->name ?? 'Primary Store' }}
            </div>
            <div class="meta-item">
                <strong>Requested Resolution</strong>
                {{ ucfirst($rma->resolution_type) }}<br>
                Reason: {{ ucwords(str_replace('_', ' ', $rma->return_reason)) }}
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th>Stated Condition</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Unit Price</th>
                    <th style="text-align: right;">Est. Refund</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rma->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product->name ?? 'Item' }}</strong>
                        @if($item->variant)
                            <br><span style="font-size: 11px; color: #6b7280;">Variant: {{ $item->variant->name }}</span>
                        @endif
                    </td>
                    <td>{{ ucwords(str_replace('_', ' ', $item->condition_stated ?? 'Like New')) }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">${{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($item->refund_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div class="total-row">Estimated Total Credit: ${{ number_format($rma->estimated_refund_amount, 2) }} {{ $rma->currency }}</div>
        </div>

        <div class="footer">
            Please enclose this authorization voucher inside the packaging. Items are subject to quality inspection upon warehouse arrival before final refund settlement.
        </div>
    </div>
</body>
</html>
