@extends('layouts.portal')

@section('title', 'Customer Returns Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header & Store Credit Wallet -->
    <div class="bg-gradient-to-r from-indigo-700 to-purple-800 rounded-2xl p-6 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-lg shadow-indigo-600/10">
        <div>
            <h1 class="text-2xl font-black mb-1">Hello, {{ $customer->name ?? auth()->user()->name }}!</h1>
            <p class="text-xs text-indigo-200">Manage your returns, exchange sizes, and monitor refund settlements.</p>
        </div>

        <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 min-w-[200px]">
            <span class="text-[11px] uppercase tracking-wider text-indigo-200 font-semibold block">Store Credit Balance</span>
            <div class="text-2xl font-black mt-0.5">${{ number_format($wallet->balance ?? 0.00, 2) }}</div>
            <span class="text-[10px] text-emerald-300">Ready for instant checkout</span>
        </div>
    </div>

    <!-- Active Returns Section -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-900">Active Return Requests ({{ $activeRmas->count() }})</h2>
            <a href="{{ route('portal.orders') }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition">
                + Start New Return
            </a>
        </div>

        @if($activeRmas->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($activeRmas as $rma)
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-sm text-slate-900">RMA #{{ $rma->rma_number }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ in_array($rma->status, ['approved', 'item_received', 'inspection_completed']) ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ ucwords(str_replace('_', ' ', $rma->status)) }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-500 mb-3">
                                Original Order: <strong>#{{ $rma->order->order_number ?? 'N/A' }}</strong> &bull;
                                Requested on {{ $rma->created_at->format('M d, Y') }}
                            </p>

                            <div class="space-y-1 mb-4 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Resolution:</span>
                                    <span class="font-semibold text-slate-800 capitalize">{{ $rma->resolution_type }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Est. Refund:</span>
                                    <span class="font-bold text-emerald-600">${{ number_format($rma->estimated_refund_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400 font-mono">{{ $rma->return_method }}</span>
                            <a href="{{ route('portal.return.show', $rma->rma_number) }}" class="text-xs font-bold text-indigo-600 hover:underline">
                                View Timeline & Documents &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white p-8 rounded-xl border border-slate-200 text-center text-slate-500 text-xs">
                You have no active returns in progress. Need to return an item from a recent order?
                <div class="mt-3">
                    <a href="{{ route('portal.orders') }}" class="font-bold text-indigo-600 hover:underline">Browse eligible orders &rarr;</a>
                </div>
            </div>
        @endif
    </div>

    <!-- Past Returns History -->
    @if($pastRmas->count() > 0)
        <div>
            <h2 class="text-lg font-bold text-slate-900 mb-4">Completed / Closed Returns</h2>
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold">
                        <tr>
                            <th class="p-3">RMA #</th>
                            <th class="p-3">Order #</th>
                            <th class="p-3">Outcome</th>
                            <th class="p-3">Settled Amount</th>
                            <th class="p-3">Closed Date</th>
                            <th class="p-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($pastRmas as $past)
                            <tr>
                                <td class="p-3 font-bold text-slate-800">{{ $past->rma_number }}</td>
                                <td class="p-3">{{ $past->order->order_number ?? 'N/A' }}</td>
                                <td class="p-3 capitalize">{{ $past->status }}</td>
                                <td class="p-3 font-semibold text-emerald-600">${{ number_format($past->approved_refund_amount, 2) }}</td>
                                <td class="p-3 text-slate-500">{{ $past->updated_at->format('M d, Y') }}</td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('portal.return.show', $past->rma_number) }}" class="text-indigo-600 font-bold hover:underline">
                                        Receipt
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
