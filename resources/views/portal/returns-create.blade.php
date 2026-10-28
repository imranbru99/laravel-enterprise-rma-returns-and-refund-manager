@extends('layouts.portal')

@section('title', 'Start Return Request — Order #' . $order->order_number)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Initiate Return Request</h1>
            <p class="text-xs text-slate-500">Order #{{ $order->order_number }} &bull; Delivered: {{ $order->delivered_at?->format('M d, Y') ?? 'Recently' }}</p>
        </div>
        <a href="{{ route('portal.orders') }}" class="text-xs font-bold text-slate-600 hover:text-indigo-600">
            &larr; Back to Orders
        </a>
    </div>

    @if(!$eligibility['is_eligible'])
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs">
            <strong>Notice:</strong> {{ $eligibility['reason'] }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form action="{{ route('portal.returns.store', $order) }}" method="POST" class="space-y-6">
            @csrf

            <!-- 1. Select Items to Return -->
            <div>
                <h3 class="font-bold text-sm text-slate-900 mb-3 border-b border-slate-100 pb-2">1. Select Products to Return</h3>
                <div class="space-y-3">
                    @foreach($order->items as $index => $item)
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <input type="hidden" name="items[{{ $index }}][order_item_id]" value="{{ $item->id }}">
                                <div>
                                    <h4 class="font-bold text-xs text-slate-900">{{ $item->product->name }}</h4>
                                    <div class="text-[11px] text-slate-500">
                                        Unit Price: ${{ number_format($item->unit_price, 2) }}
                                        @if($item->variant)
                                            &bull; Variant: {{ $item->variant->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase">Return Qty</label>
                                    <input type="number" name="items[{{ $index }}][quantity]" min="1" max="{{ $item->quantity }}" value="1"
                                        class="w-16 text-xs rounded-lg border-slate-300 py-1" />
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase">Current Condition</label>
                                    <select name="items[{{ $index }}][condition_stated]" class="text-xs rounded-lg border-slate-300 py-1">
                                        <option value="unopened">Unopened / Sealed</option>
                                        <option value="like_new" selected>Like New (Open Box)</option>
                                        <option value="opened">Used / Opened</option>
                                        <option value="damaged">Damaged in Transit</option>
                                        <option value="defective">Defective / Not Working</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 2. Return Reason & Resolution -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Primary Reason for Return</label>
                    <select name="return_reason" required class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500">
                        <option value="defective">Defective / Stopped Working</option>
                        <option value="damaged">Damaged during shipping</option>
                        <option value="wrong_item">Received Wrong Item or Color</option>
                        <option value="size_fit">Size or Fit Mismatch</option>
                        <option value="not_as_described">Item does not match website description</option>
                        <option value="customer_remorse">Changed Mind / No Longer Needed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Preferred Resolution</label>
                    <select name="resolution_type" required class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500">
                        <option value="refund">Refund to Original Payment Method</option>
                        <option value="store_credit">Instant Store Credit (+5% bonus)</option>
                        <option value="exchange">Exchange for Different Size or Color</option>
                        <option value="replacement">Free Defect Replacement</option>
                        <option value="repair">Warranty Inspection & Repair</option>
                    </select>
                </div>
            </div>

            <!-- 3. Return Method -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Return / Drop-off Method</label>
                <select name="return_method" required class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500">
                    <option value="mail_in">Prepaid Mail-in Label (Drop at any Carrier Location)</option>
                    <option value="store_dropoff">Drop-off at Store Branch (Immediate Check-in)</option>
                    <option value="courier_pickup">Schedule Courier Doorstep Pickup</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Additional Notes for Support Agent (Optional)</label>
                <textarea name="customer_notes" rows="3" class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500" placeholder="Describe any specific issues, symptoms, or requested replacement sizes..."></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">Returns are processed under our standard 30-day policy.</span>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                    Submit Return Request &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
