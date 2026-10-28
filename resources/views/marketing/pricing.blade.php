@extends('layouts.marketing')

@section('title', 'Simple, Transparent Pricing Plans — Returns & Refund Manager')

@section('content')
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-600">SaaS Subscriptions</span>
            <h1 class="text-4xl font-black text-slate-900 mt-2 mb-4">Scalable Plans for Any Volume</h1>
            <p class="text-sm text-slate-600 leading-relaxed">
                Start with a 14-day free trial. No credit card required. Upgrade, downgrade, or cancel at any time.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @forelse($plans as $plan)
                <div class="rounded-2xl border p-6 flex flex-col justify-between transition
                    {{ $plan->key === 'growth' ? 'bg-white border-indigo-600 shadow-xl shadow-indigo-500/10 ring-2 ring-indigo-600' : 'bg-white border-slate-200 shadow-sm' }}">
                    <div>
                        @if($plan->key === 'growth')
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-600 text-white mb-3 uppercase tracking-wider">
                                Most Popular
                            </span>
                        @endif

                        <h3 class="font-extrabold text-xl text-slate-900 mb-1">{{ $plan->name }}</h3>
                        <p class="text-xs text-slate-500 mb-6">{{ $plan->description }}</p>

                        <div class="mb-6">
                            <span class="text-4xl font-black text-slate-900">${{ number_format($plan->price_monthly, 0) }}</span>
                            <span class="text-xs text-slate-500 font-semibold">/ month</span>
                            <div class="text-[11px] text-slate-400 mt-1">or ${{ number_format($plan->price_annual, 0) }}/year billed annually</div>
                        </div>

                        <ul class="space-y-2.5 text-xs text-slate-600 border-t border-slate-100 pt-6 mb-6">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                <span><strong>{{ $plan->max_branches }}</strong> branch locations</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                <span><strong>{{ number_format($plan->max_monthly_rmas) }}</strong> monthly RMAs</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                <span>Full RMA & Return Engine</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 {{ $plan->has_ai_access ? 'text-emerald-500' : 'text-slate-300' }} shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                <span class="{{ $plan->has_ai_access ? '' : 'text-slate-400 line-through' }}">AI Classification & Fraud Risk</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 {{ $plan->has_white_label ? 'text-emerald-500' : 'text-slate-300' }} shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                <span class="{{ $plan->has_white_label ? '' : 'text-slate-400 line-through' }}">White-Label Custom Domain</span>
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('register') }}?plan={{ $plan->key }}"
                        class="w-full py-2.5 px-4 rounded-xl text-center text-xs font-bold transition
                            {{ $plan->key === 'growth' ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-md' : 'bg-slate-100 text-slate-800 hover:bg-slate-200' }}">
                        Start 14-Day Trial &rarr;
                    </a>
                </div>
            @empty
                <div class="col-span-4 text-center py-12 text-slate-500">
                    Pricing tiers will appear here once seeded.
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
