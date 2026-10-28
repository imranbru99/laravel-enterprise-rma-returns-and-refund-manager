@extends('layouts.marketing')

@section('title', 'Industry Solutions — Returns & Refund Manager')

@section('content')
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-600">Tailored Operational Models</span>
            <h1 class="text-4xl font-black text-slate-900 mt-2 mb-4">One Engine. Configurable for Every Industry.</h1>
            <p class="text-sm text-slate-600 leading-relaxed">
                Configure return policies, workflows, and modules to match your exact business model without writing custom code.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Retail -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mb-4 text-sm">
                        🏬
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">Omnichannel Retail Chains</h3>
                    <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                        Cross-branch returns: buy in Dhaka, exchange in Chattogram. Multi-location inventory visibility, POS integrations, and instant in-store drop-offs.
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 text-xs font-semibold text-indigo-600">
                    Recommended: RMA + Multi-Branch + Exchanges + Store Credit
                </div>
            </div>

            <!-- E-Commerce -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold mb-4 text-sm">
                        🛒
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">E-Commerce Brands & D2C</h3>
                    <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                        Self-service customer return portal, automated courier label generation with barcodes, instant variant sizing exchanges, and gateway refunds.
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 text-xs font-semibold text-purple-600">
                    Recommended: RMA + Logistics + Refunds + Customer Portal
                </div>
            </div>

            <!-- Electronics & Service Centers -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold mb-4 text-sm">
                        ⚙️
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">Electronics & Service Centers</h3>
                    <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                        Warranty tracking by device serial number, repair job ticket assignments, diagnostic steps, labor costs, and spare parts stock consumption.
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 text-xs font-semibold text-blue-600">
                    Recommended: Warranty + Repairs + Spare Parts + RMA
                </div>
            </div>

            <!-- 3PL Logistics -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold mb-4 text-sm">
                        🚚
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">3PL & Reverse Logistics Providers</h3>
                    <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                        Manage reverse logistics workflows for dozens of merchant clients. High-speed warehouse intake scanning, condition grading, and client-level reporting.
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 text-xs font-semibold text-amber-600">
                    Recommended: Multi-Tenant + Logistics + Inspection + Disposition
                </div>
            </div>

            <!-- Manufacturers -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold mb-4 text-sm">
                        🏭
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">Manufacturers & Wholesalers</h3>
                    <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                        Defect root-cause analysis, batch tracking, supplier warranty recovery (Return-to-Vendor), recycling compliance, and product recall handling.
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 text-xs font-semibold text-emerald-600">
                    Recommended: AI Defect Insights + Supplier Recovery + Warranty
                </div>
            </div>

            <!-- Fashion & Apparel -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center font-bold mb-4 text-sm">
                        👗
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">Fashion & Apparel Brands</h3>
                    <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                        Turn fit and sizing returns into exchanges instead of lost revenue. Rapid restocking of unworn items, dry cleaning grading, and store credit incentives.
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 text-xs font-semibold text-pink-600">
                    Recommended: Sizing Exchanges + Store Credit + Restocking
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
