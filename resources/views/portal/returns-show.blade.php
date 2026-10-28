@extends('layouts.portal')

@section('title', 'RMA #' . $rma->rma_number . ' — Return Tracking & Documents')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto">
    <!-- Top Details Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-4 mb-6">
            <div>
                <span class="text-xs text-slate-400 font-bold uppercase">Return Merchandise Authorization</span>
                <h1 class="text-2xl font-black text-slate-900 mt-0.5">RMA #{{ $rma->rma_number }}</h1>
                <p class="text-xs text-slate-500">Order Reference: <strong>#{{ $rma->order->order_number ?? 'N/A' }}</strong> &bull; Submitted on {{ $rma->created_at->format('M d, Y') }}</p>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase
                    {{ in_array($rma->status, ['refunded', 'closed', 'exchanged']) ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                    {{ ucwords(str_replace('_', ' ', $rma->status)) }}
                </span>
            </div>
        </div>

        <!-- Action Document Buttons -->
        <div class="flex flex-wrap items-center gap-3 mb-6 p-4 rounded-xl bg-slate-50 border border-slate-200">
            <span class="text-xs font-bold text-slate-700">Downloads & Slips:</span>
            <a href="{{ route('documents.rma.label', $rma) }}" target="_blank"
                class="px-3 py-1.5 bg-white border border-slate-300 hover:border-indigo-500 hover:text-indigo-600 rounded-lg text-xs font-bold text-slate-700 shadow-sm transition">
                🖨️ Download Prepaid Shipping Label (PDF)
            </a>
            <a href="{{ route('documents.rma.voucher', $rma) }}" target="_blank"
                class="px-3 py-1.5 bg-white border border-slate-300 hover:border-indigo-500 hover:text-indigo-600 rounded-lg text-xs font-bold text-slate-700 shadow-sm transition">
                📄 Print Drop-Off Authorization Slip
            </a>
        </div>

        <!-- Returned Items Summary -->
        <div class="mb-6">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Items Included in this RMA</h3>
            <div class="divide-y divide-slate-100">
                @foreach($rma->items as $item)
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-bold text-slate-900">{{ $item->product->name }}</span>
                            <span class="text-slate-400"> (Qty: {{ $item->quantity }})</span>
                            <div class="text-[11px] text-slate-500">Condition: {{ ucwords(str_replace('_', ' ', $item->condition_stated ?? 'Like New')) }}</div>
                        </div>
                        <div class="text-right font-semibold text-slate-800">
                            ${{ number_format($item->refund_amount, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Timeline / State History -->
        <div>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Live Return Progress Timeline</h3>
            <div class="space-y-4 relative before:absolute before:inset-0 before:left-3.5 before:w-0.5 before:bg-slate-200">
                @forelse($rma->statusHistories as $history)
                    <div class="flex items-start gap-3 relative">
                        <span class="w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold shrink-0 z-10">
                            ✓
                        </span>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs w-full">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-slate-800 capitalize">{{ ucwords(str_replace('_', ' ', $history->to_status)) }}</span>
                                <span class="text-[11px] text-slate-400">{{ $history->created_at->format('M d, H:i') }}</span>
                            </div>
                            @if($history->notes)
                                <p class="text-[11px] text-slate-600">{{ $history->notes }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-xs text-slate-400 italic">No historical transitions recorded yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Communication & Mentions Box -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-900 mb-4">Customer Support Conversation</h3>

        <div class="space-y-3 mb-6">
            @forelse($rma->comments as $comment)
                <div class="p-3 rounded-xl text-xs {{ $comment->is_internal ? 'bg-amber-50 text-amber-900' : 'bg-slate-50 text-slate-800' }} border border-slate-200">
                    <div class="flex items-center justify-between mb-1 font-semibold text-[11px]">
                        <span>{{ $comment->user->name ?? 'Customer Support' }}</span>
                        <span class="text-slate-400 font-normal">{{ $comment->created_at->format('M d, H:i') }}</span>
                    </div>
                    <p>{{ $comment->message }}</p>
                </div>
            @empty
                <div class="text-xs text-slate-400 italic py-2">No messages exchanged yet. Use the field below to message support.</div>
            @endforelse
        </div>

        <form action="{{ route('portal.return.comment', $rma) }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="message" required placeholder="Type a message to customer support..."
                class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500" />
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shrink-0 transition">
                Send Message
            </button>
        </form>
    </div>

    <!-- Dispute / Appeal Box -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-900 mb-1">Dispute / Re-inspection Appeal</h3>
        <p class="text-xs text-slate-500 mb-4">If you disagree with an inspection grade or refund deduction, submit a formal appeal for executive review.</p>

        <form action="{{ route('portal.return.dispute', $rma) }}" method="POST" class="space-y-3">
            @csrf
            <textarea name="reason" rows="2" required placeholder="Explain your objection or reason for appeal..."
                class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500"></textarea>
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition">
                Register Dispute Appeal
            </button>
        </form>
    </div>
</div>
@endsection
