@extends('layouts.marketing')

@section('title', 'Complete 90-Module Enterprise Catalog — Returns & Refund Manager')

@section('content')
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-600">Enterprise Feature Catalog</span>
            <h1 class="text-4xl font-black text-slate-900 mt-2 mb-4">90 Modular Capabilities Built for Modern Commerce</h1>
            <p class="text-sm text-slate-600 leading-relaxed">
                Every business model is supported. Enable only the modules you need for your operational workflow—from a single retail shop to multinational 3PL networks.
            </p>
        </div>

        <div class="space-y-12">
            <!-- Core Operations Family -->
            <div>
                <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-indigo-600"></span>
                    Core RMA & Customer Operations
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">1. RMA Core Engine</h4>
                        <p class="text-xs text-slate-600">Complete return request lifecycle, approvals, status histories, priority SLA timers, and automatic routing.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">2. Policy & Eligibility Engine</h4>
                        <p class="text-xs text-slate-600">Configurable return windows, receipt verification, category rules, and auto-approval threshold engines.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">3. Exchanges & Variant Swaps</h4>
                        <p class="text-xs text-slate-600">Same-product, different size, or cross-product swaps with automated price difference calculations and stock reservation.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">4. Replacement Management</h4>
                        <p class="text-xs text-slate-600">Fulfill defect replacements, advance replacements, and warranty fulfillments with full traceability.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">5. Customer Self-Service Portal</h4>
                        <p class="text-xs text-slate-600">Dedicated portal for order lookup, returns creation, label download, drop-off voucher printing, and live tracking.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">6. Customer Dispute Resolution</h4>
                        <p class="text-xs text-slate-600">Structured dispute submission, senior manager review escalations, and recorded customer communication trails.</p>
                    </div>
                </div>
            </div>

            <!-- Warehouse & Logistics Family -->
            <div>
                <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-600"></span>
                    Warehouse, Logistics & Disposition
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">7. Warehouse Receiving & Intake</h4>
                        <p class="text-xs text-slate-600">Inbound package scanning, multi-warehouse intake, and cross-branch return routing.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">8. Inspection & Grading</h4>
                        <p class="text-xs text-slate-600">Item condition checklists, photo evidence uploads, grading (like-new, used, defective, scrap), and inspector attribution.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">9. Inventory Restocking & Reconciliation</h4>
                        <p class="text-xs text-slate-600">Instant stock replenishment, movement logging, inventory valuation updates, and scrap loss accounting.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">10. Reverse Logistics & Shipping</h4>
                        <p class="text-xs text-slate-600">Prepaid shipping labels with barcodes, carrier integration adapters (FedEx, DHL, UPS, Mock), and webhook tracking events.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">11. Disposition & Supplier Recovery (RTV)</h4>
                        <p class="text-xs text-slate-600">Route inspected goods to restock, refurbish, recycle, salvage, or return to vendor for supplier warranty credit.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">12. Inter-Branch Stock Transfers</h4>
                        <p class="text-xs text-slate-600">Transfer replacement stock between branches with automated inventory adjustments at origin and destination.</p>
                    </div>
                </div>
            </div>

            <!-- Financial Operations Family -->
            <div>
                <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                    Financial Operations & Ledgers
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">13. Safe Gateway Refunds</h4>
                        <p class="text-xs text-slate-600">Idempotency-locked payouts, remaining refundable balance checks, and support for Stripe, PayPal, and manual branch cash.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">14. Store Credit & Digital Wallets</h4>
                        <p class="text-xs text-slate-600">Instant wallet balance refunds, transaction logs, and customer store credit redemption.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">15. Multilingual Credit Notes</h4>
                        <p class="text-xs text-slate-600">PDF credit note generation in English, Bengali, and Arabic via UnicodePdf integration with RTL support.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">16. Double-Entry Financial Journals</h4>
                        <p class="text-xs text-slate-600">Immutable general ledger entries recording debits to returns allowances and credits to gateway clearing accounts.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">17. Tax & Shipping Adjustments</h4>
                        <p class="text-xs text-slate-600">Automatic tax recalculation, restocking fee deductions, and return shipping fee adjustments.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">18. Refund Reconciliation</h4>
                        <p class="text-xs text-slate-600">Reconcile provider settlement webhooks with internal financial transactions.</p>
                    </div>
                </div>
            </div>

            <!-- AI & After-Sales Family -->
            <div>
                <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-purple-600"></span>
                    AI Intelligence & After-Sales Warranty
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">19. AI Reason Classification</h4>
                        <p class="text-xs text-slate-600">Powered by AIHub: analyzes customer descriptions, tags hardware/sizing defects, and assigns priority.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">20. AI Fraud Risk Scoring</h4>
                        <p class="text-xs text-slate-600">Calculates 0-100 risk score based on return frequency, delivery speed, and high-value indicators.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">21. Warranty Claims & Serial Tracking</h4>
                        <p class="text-xs text-slate-600">Serial number verification, purchase date checks against product warranty windows, and claim approvals.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">22. Diagnostic Repairs & Spare Parts</h4>
                        <p class="text-xs text-slate-600">Technician job assignment, labor cost tracking, diagnostic steps, and spare parts inventory deduction.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">23. Policy-Grounded AI Support (RAG)</h4>
                        <p class="text-xs text-slate-600">Retrieval-augmented customer support answering questions grounded in store policy.</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-sm text-slate-900 mb-1">24. Visual Trigger Automations</h4>
                        <p class="text-xs text-slate-600">Rule-based triggers: auto-approve low value, flag high risk, and notify warehouse staff on delivery.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-16 text-center">
            <a href="{{ route('demo') }}" class="px-8 py-4 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-lg hover:bg-indigo-700 transition">
                Test These Capabilities in the Live Sandbox &rarr;
            </a>
        </div>
    </div>
</section>
@endsection
