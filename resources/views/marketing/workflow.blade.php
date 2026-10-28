@extends('layouts.marketing')

@section('title', 'Complete RMA Workflow Lifecycle — Returns & Refund Manager')

@section('content')
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-600">Traceable Lifecycle</span>
            <h1 class="text-4xl font-black text-slate-900 mt-2 mb-4">Complete RMA State Transition Engine</h1>
            <p class="text-sm text-slate-600 leading-relaxed">
                From return request submission to final disposition and general ledger reconciliation, every step is strictly enforced by state machines and audit trails.
            </p>
        </div>

        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Step 1 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                <span class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black shrink-0 text-sm">01</span>
                <div>
                    <h3 class="font-bold text-base text-slate-900 mb-1">Return Request Initiated (Draft / Pending Review)</h3>
                    <p class="text-xs text-slate-600 mb-2 leading-relaxed">
                        Customer initiates a return via the Self-Service Portal or store agent creates an in-store request. Order line items, return reason, photos, and requested resolution (Refund, Exchange, Replacement, Repair) are captured.
                    </p>
                    <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-indigo-50 text-indigo-700 font-semibold">Status: pending_review</span>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                <span class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black shrink-0 text-sm">02</span>
                <div>
                    <h3 class="font-bold text-base text-slate-900 mb-1">Automated Policy & Eligibility Evaluation</h3>
                    <p class="text-xs text-slate-600 mb-2 leading-relaxed">
                        The return eligibility engine validates the order delivery date against the policy return window (e.g. 30 days), verifies remaining refundable order balance, and checks non-returnable categories. Eligible returns under the auto-approve threshold are approved immediately.
                    </p>
                    <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-emerald-50 text-emerald-700 font-semibold">Status: approved</span>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                <span class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black shrink-0 text-sm">03</span>
                <div>
                    <h3 class="font-bold text-base text-slate-900 mb-1">Prepaid Label & Voucher Generation</h3>
                    <p class="text-xs text-slate-600 mb-2 leading-relaxed">
                        Upon approval, the system generates a prepaid courier label with barcode and carrier tracking number, or a store drop-off voucher slip for in-person return.
                    </p>
                    <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-blue-50 text-blue-700 font-semibold">Status: label_generated &rarr; item_shipped</span>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                <span class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black shrink-0 text-sm">04</span>
                <div>
                    <h3 class="font-bold text-base text-slate-900 mb-1">Warehouse Receiving & Barcode Intake</h3>
                    <p class="text-xs text-slate-600 mb-2 leading-relaxed">
                        The warehouse receives the package and scans the RMA barcode. The package is checked in and queued for physical quality grading. Supports multi-warehouse receiving and cross-branch drop-offs.
                    </p>
                    <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-purple-50 text-purple-700 font-semibold">Status: item_received</span>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                <span class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black shrink-0 text-sm">05</span>
                <div>
                    <h3 class="font-bold text-base text-slate-900 mb-1">Quality Inspection & Grading</h3>
                    <p class="text-xs text-slate-600 mb-2 leading-relaxed">
                        Inspector evaluates the item against standard checklists: unopened, like-new, used, defective, or damaged. Serial numbers are confirmed and photos uploaded. Suggested disposition is assigned.
                    </p>
                    <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-cyan-50 text-cyan-700 font-semibold">Status: inspecting &rarr; inspection_completed</span>
                </div>
            </div>

            <!-- Step 6 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                <span class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black shrink-0 text-sm">06</span>
                <div>
                    <h3 class="font-bold text-base text-slate-900 mb-1">Final Settlement (Refund, Exchange, Replacement, Repair)</h3>
                    <p class="text-xs text-slate-600 mb-2 leading-relaxed">
                        - **Refund**: Processed to original payment method or instant store credit wallet. Verified against refundable balance with idempotency locks.<br>
                        - **Exchange**: Replacement stock reserved, price difference settled, exchange order dispatched.<br>
                        - **Repair**: Job assigned to technician with spare parts deduction.
                    </p>
                    <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-emerald-50 text-emerald-700 font-semibold">Status: refunded / exchanged / repaired</span>
                </div>
            </div>

            <!-- Step 7 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                <span class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black shrink-0 text-sm">07</span>
                <div>
                    <h3 class="font-bold text-base text-slate-900 mb-1">Financial Reconciliation & Case Closure</h3>
                    <p class="text-xs text-slate-600 mb-2 leading-relaxed">
                        Double-entry journal posted to general ledger. Multilingual Credit Note generated (English, Bengali, Arabic). Customer notified. RMA archived with immutable audit log.
                    </p>
                    <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-slate-100 text-slate-700 font-semibold">Status: closed</span>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('demo') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow hover:bg-indigo-700 transition">
                Simulate this Full Workflow in the Sandbox Demo &rarr;
            </a>
        </div>
    </div>
</section>
@endsection
