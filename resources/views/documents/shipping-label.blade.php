<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prepaid Return Shipping Label</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 20px; color: #111827; }
        .label-container { border: 3px dashed #111827; border-radius: 8px; padding: 24px; max-width: 600px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #111827; padding-bottom: 12px; margin-bottom: 20px; }
        .carrier-title { font-size: 24px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
        .badge { background: #111827; color: #fff; padding: 4px 8px; font-weight: 700; font-size: 14px; border-radius: 4px; }
        .grid { display: flex; justify-content: space-between; margin-bottom: 24px; }
        .col { width: 48%; }
        .address-box { font-size: 13px; line-height: 1.5; background: #f3f4f6; padding: 12px; border-radius: 6px; }
        .address-title { font-weight: 700; margin-bottom: 6px; text-transform: uppercase; font-size: 11px; color: #6b7280; }
        .barcode-section { text-align: center; border-top: 2px solid #111827; padding-top: 20px; }
        .fake-barcode { font-family: 'Courier New', monospace; font-size: 28px; letter-spacing: 5px; font-weight: bold; background: #f9fafb; padding: 16px; border: 1px solid #d1d5db; display: inline-block; margin-bottom: 8px; }
        .instructions { font-size: 11px; color: #4b5563; margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 12px; line-height: 1.4; }
    </style>
</head>
<body>
    <div class="label-container">
        <div class="header">
            <div>
                <span class="carrier-title">{{ $carrier }}</span>
                <div style="font-size: 12px; color: #6b7280; margin-top: 2px;">PRIORITY COMMERCIAL RETURN SERVICE</div>
            </div>
            <div class="badge">PREPAID</div>
        </div>

        <div class="grid">
            <div class="col">
                <div class="address-box">
                    <div class="address-title">FROM (CUSTOMER):</div>
                    <strong>{{ $customer->name ?? 'Valued Customer' }}</strong><br>
                    {{ $customer->phone ?? 'Phone on record' }}<br>
                    Email: {{ $customer->email }}
                </div>
            </div>
            <div class="col">
                <div class="address-box">
                    <div class="address-title">SHIP TO (RETURNS WAREHOUSE):</div>
                    <strong>{{ $branch->name ?? 'Central Returns Hub' }}</strong><br>
                    {{ $branch->address ?? 'Returns Department' }}<br>
                    {{ $branch->city ?? 'Central Hub' }}, {{ $branch->state ?? '' }} {{ $branch->postal_code ?? '' }}<br>
                    {{ $branch->country ?? 'US' }}
                </div>
            </div>
        </div>

        <div class="barcode-section">
            <div style="font-size: 12px; font-weight: 700; margin-bottom: 4px;">TRACKING #: {{ $trackingNumber }}</div>
            <div class="fake-barcode">||| | | |||| || | ||| |||| | ||</div>
            <div style="font-size: 14px; font-weight: bold; letter-spacing: 1px;">RMA REFERENCE: {{ $rma->rma_number }}</div>
        </div>

        <div class="instructions">
            <strong>INSTRUCTIONS:</strong> Securely tape this label to the top of your return package. Remove or cover any prior shipping barcodes. Drop off at any authorized drop-off station or carrier collection box.
        </div>
    </div>
</body>
</html>
