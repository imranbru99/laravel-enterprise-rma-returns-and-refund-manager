@extends('layouts.marketing')

@section('title', 'Returns & Refund Manager — Enterprise RMA & Reverse Logistics SaaS')

@section('content')

<!-- Hero Section -->
<section class="relative overflow-hidden pt-20 pb-24 md:pt-28 md:pb-32 bg-gradient-to-b from-white via-indigo-50/20 to-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-indigo-100/80 text-indigo-700 border border-indigo-200 mb-6">
                <span class="flex h-2 w-2 rounded-full bg-indigo-600"></span>
                Enterprise Multi-Branch RMA & Reverse Logistics Platform
            </div>

            <h1 class="text-4xl sm:text-6xl font-black text-slate-900 tracking-tight leading-[1.1] mb-6">
                Turn Returns from a Cost Center into a <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600">Growth Engine</span>
            </h1>

            <p class="text-base sm:text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                A unified, AI-powered platform for Returns Merchandise Authorization, instant refunds, variant exchanges, warehouse grading, cross-branch transfers, and double-entry financial reconciliation.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                <a href="{{ route('demo') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-xl shadow-indigo-600/25 transition transform hover:-translate-y-0.5">
                    Launch Interactive Demo Showcase &rarr;
                </a>
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl text-sm font-bold text-slate-800 bg-white hover:bg-slate-50 border border-slate-300 shadow-sm transition">
                    Start 14-Day Free Trial
                </a>
            </div>

            <div class="flex items-center justify-center gap-6 text-xs font-semibold text-slate-500">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    Multi-Tenant & Multi-Branch
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    AI Return Classification
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    Full Financial Ledgers
                </div>
            </div>
        </div>

        <!-- Live Product Dashboard Preview -->
        <div class="mt-16 relative max-w-5xl mx-auto rounded-2xl p-2 bg-gradient-to-b from-slate-200 to-slate-400/20 shadow-2xl">
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-inner">
                <div class="h-10 bg-slate-100 border-b border-slate-200 px-4 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-400"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                    </div>
                    <div class="text-[11px] font-mono text-slate-500 bg-white px-6 py-1 rounded-md border border-slate-200">
                        https://app.returnsmanager.io/admin/rmas
                    </div>
                    <div class="text-xs text-slate-400 font-semibold">Enterprise Hub</div>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-4 bg-slate-50 border-b border-slate-200">
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                        <span class="text-xs text-slate-500 font-medium">Active Returns</span>
                        <div class="text-2xl font-black text-slate-900 mt-1">1,482</div>
                        <span class="text-[11px] text-emerald-600 font-semibold">&uarr; 12% faster processing</span>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                        <span class="text-xs text-slate-500 font-medium">Pending Inspection</span>
                        <div class="text-2xl font-black text-amber-600 mt-1">42</div>
                        <span class="text-[11px] text-slate-500">Across 8 branches</span>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                        <span class="text-xs text-slate-500 font-medium">Saved via Exchanges</span>
                        <div class="text-2xl font-black text-indigo-600 mt-1">$48,250</div>
                        <span class="text-[11px] text-indigo-600 font-semibold">34% exchange retention</span>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                        <span class="text-xs text-slate-500 font-medium">Reconciled Refunds</span>
                        <div class="text-2xl font-black text-emerald-600 mt-1">$124,980</div>
                        <span class="text-[11px] text-emerald-600 font-semibold">100% ledger balanced</span>
                    </div>
                </div>

                <div class="p-6 bg-white flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-sm text-slate-900">Live RMA Processing Command Center</h4>
                        <p class="text-xs text-slate-500">Full visibility across branches, warehouses, technicians, and finance approvers.</p>
                    </div>
                    <a href="{{ route('demo') }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-xs font-bold transition">
                        Explore Live Sandbox &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6 Core Value Pillars -->
<section class="py-20 bg-white border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-xs font-bold uppercase tracking-widest text-indigo-600 mb-2">Architected for Scale</h2>
            <h3 class="text-3xl font-black text-slate-900 sm:text-4xl">Every RMA capability your business needs</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <h4 class="font-bold text-lg text-slate-900 mb-2">Automated RMA Lifecycle</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Custom policy evaluation, instant auto-approval thresholds, prepaid shipping label generation, barcode tracking, and drop-off vouchers.
                </p>
            </div>

            <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="font-bold text-lg text-slate-900 mb-2">Financial Integrity & Ledgers</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Zero duplicate refund risk via idempotency locks, refundable balance checks, store credit wallet balances, and double-entry journals.
                </p>
            </div>

            <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <h4 class="font-bold text-lg text-slate-900 mb-2">Exchanges & Variant Swaps</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Seamless same-product or different-variant swaps with automatic price difference calculations, stock reservation, and tax adjustments.
                </p>
            </div>

            <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h4 class="font-bold text-lg text-slate-900 mb-2">Multi-Branch & Cross-Return</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Customer buys at Branch A and returns at Branch B. Real-time replacement inventory lookup, inter-branch stock transfers, and branch audits.
                </p>
            </div>

            <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center font-bold mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="font-bold text-lg text-slate-900 mb-2">Warehouse Grading & Intake</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Quality inspection checklists, photo evidence uploads, grading (like-new, damaged, scrap), and routing to restock, refurbish, or RTV.
                </p>
            </div>

            <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center font-bold mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h4 class="font-bold text-lg text-slate-900 mb-2">AI Defect & Fraud Intelligence</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Return reason classification, frequent defect pattern detection, serial verification, and anomaly detection to prevent return fraud.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Visual RMA Lifecycle Explorer -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-xs font-bold uppercase tracking-widest text-indigo-600 mb-2">End-to-End Traceability</h2>
            <h3 class="text-3xl font-black text-slate-900">The 7-Step Universal RMA Lifecycle</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-7 gap-3 text-center">
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center mx-auto mb-2 text-xs">1</div>
                <h5 class="text-xs font-bold text-slate-900 mb-1">Request</h5>
                <p class="text-[11px] text-slate-500">Customer portal or in-store scan</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center mx-auto mb-2 text-xs">2</div>
                <h5 class="text-xs font-bold text-slate-900 mb-1">Eligibility</h5>
                <p class="text-[11px] text-slate-500">Policy window & receipt check</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center mx-auto mb-2 text-xs">3</div>
                <h5 class="text-xs font-bold text-slate-900 mb-1">Approval</h5>
                <p class="text-[11px] text-slate-500">Auto-approve or agent review</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center mx-auto mb-2 text-xs">4</div>
                <h5 class="text-xs font-bold text-slate-900 mb-1">Shipping</h5>
                <p class="text-[11px] text-slate-500">Prepaid label & tracking</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center mx-auto mb-2 text-xs">5</div>
                <h5 class="text-xs font-bold text-slate-900 mb-1">Intake</h5>
                <p class="text-[11px] text-slate-500">Warehouse barcode scan</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center mx-auto mb-2 text-xs">6</div>
                <h5 class="text-xs font-bold text-slate-900 mb-1">Inspection</h5>
                <p class="text-[11px] text-slate-500">Grading & disposition</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center mx-auto mb-2 text-xs">7</div>
                <h5 class="text-xs font-bold text-slate-900 mb-1">Settlement</h5>
                <p class="text-[11px] text-slate-500">Refund, swap, or repair</p>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('workflow') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 underline">
                View interactive workflow diagram and branching policies &rarr;
            </a>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-20 bg-gradient-to-r from-indigo-900 via-indigo-800 to-purple-900 text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-3xl sm:text-5xl font-black mb-6 tracking-tight">Ready to modernise your returns operations?</h3>
        <p class="text-base sm:text-lg text-indigo-200 mb-10 max-w-2xl mx-auto">
            Experience the complete platform in the live interactive sandbox demo. No credit card required.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('demo') }}" class="px-8 py-4 rounded-xl text-sm font-bold text-indigo-950 bg-white hover:bg-slate-100 shadow-xl transition">
                Launch Sandbox Demo &rarr;
            </a>
            <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 border border-indigo-400 transition">
                Register Your Company
            </a>
        </div>
    </div>
</section>

@endsection
