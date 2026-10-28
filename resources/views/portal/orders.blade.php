@extends('layouts.portal')

@section('title', 'My Orders & Return Eligibility')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Your Orders</h1>
            <p class="text-xs text-slate-500">Orders eligible for returns or variant exchanges under your store policy.</p>
        </div>
        <a href="{{ route('portal.dashboard') }}" class="text-xs font-bold text-slate-600 hover:text-indigo-600">
            &larr; Back to Dashboard
        </a>
    </div>

    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2 mb-4">
                        <div>
                            <span class="font-bold text-sm text-slate-900">Order #{{ $order->order_number }}</span>
                            <div class="text-xs text-slate-400 mt-0.5">
                                Placed on {{ $order->ordered_at?->format('M d, Y') ?? $order->created_at->format('M d, Y') }}
                                &bull; {{ ucfirst($order->channel) }}
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-700">Total: ${{ number_format($order->total_amount, 2) }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Items list -->
                    <div class="divide-y divide-slate-100 mb-4">
                        @foreach($order->items as $item)
                            <div class="py-2.5 flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-bold text-slate-800">{{ $item->product->name ?? 'Product' }}</span>
                                    <span class="text-slate-400"> (Qty: {{ $item->quantity }})</span>
                                    <div class="text-[11px] text-slate-500">${{ number_format($item->unit_price, 2) }} each</div>
                                </div>
                                <div>
                                    <span class="text-slate-700 font-semibold">${{ number_format($item->total_amount, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <span class="text-xs text-slate-500">
                            Refundable balance remaining: <strong class="text-emerald-600">${{ number_format($order->remaining_refundable_amount, 2) }}</strong>
                        </span>

                        @if($order->remaining_refundable_amount > 0)
                            <a href="{{ route('portal.returns.create', $order) }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition">
                                Start Return Request &rarr;
                            </a>
                        @else
                            <span class="text-xs text-slate-400 italic">Fully Refunded</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-white p-12 rounded-xl border border-slate-200 text-center text-slate-500 text-xs">
            No orders found under your account.
        </div>
    @endif
</div>
@endsection
