@extends('layouts.marketing')

@section('title', 'API Reference & Documentation — Returns & Refund Manager')

@section('content')
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-600">Developer Platform</span>
            <h1 class="text-4xl font-black text-slate-900 mt-2 mb-4">REST API & Webhooks Reference</h1>
            <p class="text-sm text-slate-600 leading-relaxed">
                Seamlessly integrate your custom storefronts, ERP systems, Shopify stores, and courier services via our secure, versioned JSON REST API.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-1 space-y-2 text-xs">
                <a href="#auth" class="block p-2 rounded-lg font-bold bg-indigo-50 text-indigo-700">Authentication (Sanctum)</a>
                <a href="#rmas" class="block p-2 rounded-lg text-slate-600 hover:bg-slate-100">RMA Management Endpoints</a>
                <a href="#refunds" class="block p-2 rounded-lg text-slate-600 hover:bg-slate-100">Refund Processing Endpoints</a>
                <a href="#webhooks" class="block p-2 rounded-lg text-slate-600 hover:bg-slate-100">Outgoing Webhooks & Signatures</a>
                <a href="#tenancy" class="block p-2 rounded-lg text-slate-600 hover:bg-slate-100">Multi-Tenancy & Headers</a>
            </div>

            <div class="md:col-span-3 space-y-10">
                <!-- Auth Section -->
                <div id="auth" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Authentication</h3>
                    <p class="text-xs text-slate-600 mb-4">
                        All API requests require a Bearer token generated via Laravel Sanctum or your tenant API credentials.
                    </p>
                    <div class="bg-slate-900 text-slate-100 p-4 rounded-xl text-xs font-mono overflow-x-auto">
                        curl -X GET "https://app.returnsmanager.io/api/v1/rmas" \<br>
                        &nbsp;&nbsp;-H "Authorization: Bearer YOUR_API_TOKEN" \<br>
                        &nbsp;&nbsp;-H "Accept: application/json"
                    </div>
                </div>

                <!-- RMAs Endpoints -->
                <div id="rmas" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">RMA Endpoints</h3>

                    <div class="space-y-4">
                        <div class="border border-slate-100 rounded-xl p-4 bg-slate-50/50">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-600 text-white font-mono">GET</span>
                                <span class="font-mono text-xs font-bold text-slate-800">/api/v1/rmas</span>
                            </div>
                            <p class="text-xs text-slate-600">List paginated returns filtered by status, branch, or date range.</p>
                        </div>

                        <div class="border border-slate-100 rounded-xl p-4 bg-slate-50/50">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-600 text-white font-mono">POST</span>
                                <span class="font-mono text-xs font-bold text-slate-800">/api/v1/rmas</span>
                            </div>
                            <p class="text-xs text-slate-600 mb-2">Create a new return authorization request.</p>
                            <div class="bg-slate-900 text-slate-100 p-3 rounded-lg text-[11px] font-mono">
                                {<br>
                                &nbsp;&nbsp;"order_id": 142,<br>
                                &nbsp;&nbsp;"return_reason": "defective",<br>
                                &nbsp;&nbsp;"resolution_type": "refund",<br>
                                &nbsp;&nbsp;"items": [<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;{ "order_item_id": 89, "quantity": 1, "condition_stated": "like_new" }<br>
                                &nbsp;&nbsp;]<br>
                                }
                            </div>
                        </div>

                        <div class="border border-slate-100 rounded-xl p-4 bg-slate-50/50">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-600 text-white font-mono">GET</span>
                                <span class="font-mono text-xs font-bold text-slate-800">/api/v1/rmas/{rma_number}</span>
                            </div>
                            <p class="text-xs text-slate-600">Retrieve real-time RMA status, inspection grades, and tracking numbers.</p>
                        </div>
                    </div>
                </div>

                <!-- Webhooks -->
                <div id="webhooks" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">HMAC-Signed Webhooks</h3>
                    <p class="text-xs text-slate-600 mb-4">
                        Receive instant notifications when an RMA is approved, item received at warehouse, or refund settled. All payloads are signed using HMAC-SHA256 headers.
                    </p>
                    <div class="bg-slate-900 text-slate-100 p-3 rounded-lg text-[11px] font-mono">
                        X-Returns-Signature: sha256=d3b07384d113edec49eaa6238ad5ff00 ...
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
