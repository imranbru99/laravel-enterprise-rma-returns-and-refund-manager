<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Return Classification Box -->
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-4 border-b border-gray-100 dark:border-gray-800 pb-3">
                <x-filament::icon icon="heroicon-o-tag" class="w-5 h-5 text-indigo-600" />
                <h3 class="text-base font-bold text-gray-900 dark:text-white">1. AI Return Reason Classification & Triage</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Product Category</label>
                    <input type="text" wire:model="testCategory" class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Stated Return Reason</label>
                    <input type="text" wire:model="testReason" class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800" />
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Detailed Customer Description / Photos Notes</label>
                <textarea wire:model="testNotes" rows="2" class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800"></textarea>
            </div>

            <button type="button" wire:click="runClassification" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition">
                Execute AI Classification
            </button>

            @if($classificationResult)
                <div class="mt-4 p-4 rounded-lg bg-indigo-50/60 dark:bg-gray-800 border border-indigo-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-900 dark:text-indigo-300">Suggested Category:</span>
                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-indigo-600 text-white">
                            {{ $classificationResult['suggested_category'] }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 mb-2 text-xs">
                        <span class="font-semibold text-gray-600 dark:text-gray-400">Confidence Score:</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ round($classificationResult['confidence'] * 100) }}%</span>
                        <span class="mx-2 text-gray-300">|</span>
                        <span class="font-semibold text-gray-600 dark:text-gray-400">Recommended Priority:</span>
                        <span class="font-bold uppercase text-amber-600">{{ $classificationResult['priority'] }}</span>
                    </div>
                    @if(!empty($classificationResult['defect_tags']))
                        <div class="flex items-center gap-1.5 text-xs">
                            <span class="font-semibold text-gray-600 dark:text-gray-400">Auto-Tagged Defect Flags:</span>
                            @foreach($classificationResult['defect_tags'] as $tag)
                                <span class="px-2 py-0.5 rounded text-[11px] bg-white dark:bg-gray-700 border font-mono">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Fraud Risk Evaluation Box -->
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-4 border-b border-gray-100 dark:border-gray-800 pb-3">
                <x-filament::icon icon="heroicon-o-shield-exclamation" class="w-5 h-5 text-amber-600" />
                <h3 class="text-base font-bold text-gray-900 dark:text-white">2. AI Fraud Risk Scoring & Anomaly Detection</h3>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Select Return Case (RMA) to Evaluate</label>
                <select wire:model="selectedRmaId" class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                    @foreach(\App\Models\Rma::with('customer')->latest()->take(10)->get() as $rma)
                        <option value="{{ $rma->id }}">{{ $rma->rma_number }} — {{ $rma->customer->name ?? 'Customer' }} (${{ number_format($rma->estimated_refund_amount, 2) }})</option>
                    @endforeach
                </select>
            </div>

            <button type="button" wire:click="runFraudScoring" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-bold transition">
                Evaluate Fraud Risk
            </button>

            @if($fraudRiskResult)
                <div class="mt-4 p-4 rounded-lg bg-amber-50/60 dark:bg-gray-800 border border-amber-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200">Risk Score (0-100):</span>
                        <span class="px-3 py-1 rounded text-sm font-black {{ $fraudRiskResult['risk_score'] >= 70 ? 'bg-red-600 text-white' : ($fraudRiskResult['risk_score'] >= 40 ? 'bg-amber-500 text-white' : 'bg-emerald-600 text-white') }}">
                            {{ $fraudRiskResult['risk_score'] }}/100
                        </span>
                    </div>

                    <div class="text-xs font-semibold text-gray-800 dark:text-gray-200 mb-2">
                        Recommendation: <span class="font-normal">{{ $fraudRiskResult['recommendation'] }}</span>
                    </div>

                    @if(!empty($fraudRiskResult['flags']))
                        <div class="text-xs text-red-600 dark:text-red-400 font-semibold mb-1">Observed Risk Indicators:</div>
                        <ul class="list-disc list-inside text-xs text-gray-600 dark:text-gray-300 space-y-1">
                            @foreach($fraudRiskResult['flags'] as $flag)
                                <li>{{ $flag }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">No suspicious behavioral patterns detected.</div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Customer Q&A Box -->
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-4 border-b border-gray-100 dark:border-gray-800 pb-3">
                <x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="w-5 h-5 text-emerald-600" />
                <h3 class="text-base font-bold text-gray-900 dark:text-white">3. Intelligent Customer Policy Assistant (RAG)</h3>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Customer Query</label>
                <input type="text" wire:model="customerQuery" class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800" />
            </div>

            <button type="button" wire:click="runCustomerSupport" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition">
                Generate Policy-Grounded Answer
            </button>

            @if($aiReply)
                <div class="mt-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-800 border text-xs text-gray-800 dark:text-gray-200 leading-relaxed">
                    <strong>AI Assistant:</strong> {{ $aiReply }}
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
