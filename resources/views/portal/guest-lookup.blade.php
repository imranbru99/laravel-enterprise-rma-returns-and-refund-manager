@extends('layouts.portal')

@section('title', 'Guest Return & Order Lookup')

@section('content')
<div class="max-w-md mx-auto py-12">
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        <h2 class="text-xl font-black text-slate-900 mb-1">Guest Return Lookup</h2>
        <p class="text-xs text-slate-500 mb-6">Enter your RMA number and the email address used during purchase.</p>

        <form action="{{ route('portal.guest') }}" method="GET" class="space-y-4 mb-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">RMA Reference Number</label>
                <input type="text" name="rma_number" value="{{ request('rma_number') }}" required placeholder="e.g. RMA-202610-A1B2C3"
                    class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 font-mono" />
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email on Record</label>
                <input type="email" name="email" value="{{ request('email') }}" required placeholder="your.email@example.com"
                    class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500" />
            </div>

            <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow transition">
                Look Up Return Status &rarr;
            </button>
        </form>

        @if(request()->filled('rma_number'))
            @if($rma)
                <div class="p-4 rounded-xl bg-indigo-50 border border-indigo-200 text-xs">
                    <div class="font-bold text-indigo-900 mb-1">Return Found: {{ $rma->rma_number }}</div>
                    <div class="text-slate-600 mb-2">Status: <strong class="uppercase">{{ $rma->status }}</strong></div>
                    <a href="{{ route('portal.return.show', $rma->rma_number) }}" class="inline-block px-3 py-1 bg-indigo-600 text-white rounded font-bold hover:bg-indigo-700">
                        View Tracking Timeline & Slip &rarr;
                    </a>
                </div>
            @else
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs">
                    No matching return found for that RMA number and email address combination. Please verify your details.
                </div>
            @endif
        @endif

        <div class="mt-6 pt-6 border-t border-slate-100 text-center">
            <a href="{{ route('portal.login') }}" class="text-xs text-indigo-600 font-bold hover:underline">
                Have an account? Log in here &rarr;
            </a>
        </div>
    </div>
</div>
@endsection
