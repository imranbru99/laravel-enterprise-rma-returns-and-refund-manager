@extends('layouts.marketing')

@section('title', 'Interactive Sandbox Demo Showcase — Returns & Refund Manager')

@section('content')
<section class="py-16 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                ISOLATED SANDBOX ENVIRONMENT — ZERO REAL PAYMENTS OR PRODUCTION DATA
            </span>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 mt-3 mb-2">Live Interactive RMA Lifecycle Demo</h1>
            <p class="text-xs sm:text-sm text-slate-600">
                Click any step below to simulate how returns advance through our backend state machine in real time.
            </p>
        </div>

        <!-- Interactive Sandbox Step Simulator -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm mb-12" id="sandbox-container">
            <div class="flex flex-col sm:flex-row items-center justify-between border-b border-slate-100 pb-4 mb-6 gap-4">
                <div>
                    <span class="text-xs text-slate-400 font-bold uppercase">Active Demo Return Record</span>
                    <h3 class="text-lg font-black text-slate-900" id="display-rma-number">
                        {{ $demoRma->rma_number ?? 'RMA-202610-DEMO' }}
                    </h3>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-slate-500">Current Status:</span>
                    <span id="display-status-badge" class="px-3 py-1 rounded-full text-xs font-black uppercase bg-indigo-100 text-indigo-700">
                        {{ $demoRma->status ?? 'pending_review' }}
                    </span>
                </div>
            </div>

            <!-- Step Buttons -->
            <div class="grid grid-cols-2 sm:grid-cols-6 gap-3 mb-6">
                <button onclick="advanceStep('submit')" class="p-3 rounded-xl border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50/50 text-left transition group">
                    <span class="block text-[10px] font-bold text-slate-400 group-hover:text-indigo-600">STEP 1</span>
                    <span class="text-xs font-bold text-slate-900">Submit Return</span>
                </button>
                <button onclick="advanceStep('approve')" class="p-3 rounded-xl border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50/50 text-left transition group">
                    <span class="block text-[10px] font-bold text-slate-400 group-hover:text-indigo-600">STEP 2</span>
                    <span class="text-xs font-bold text-slate-900">Approve Request</span>
                </button>
                <button onclick="advanceStep('receive')" class="p-3 rounded-xl border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50/50 text-left transition group">
                    <span class="block text-[10px] font-bold text-slate-400 group-hover:text-indigo-600">STEP 3</span>
                    <span class="text-xs font-bold text-slate-900">Warehouse Intake</span>
                </button>
                <button onclick="advanceStep('inspect')" class="p-3 rounded-xl border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50/50 text-left transition group">
                    <span class="block text-[10px] font-bold text-slate-400 group-hover:text-indigo-600">STEP 4</span>
                    <span class="text-xs font-bold text-slate-900">Grade & Inspect</span>
                </button>
                <button onclick="advanceStep('refund')" class="p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 text-left transition group">
                    <span class="block text-[10px] font-bold text-slate-400 group-hover:text-emerald-600">STEP 5</span>
                    <span class="text-xs font-bold text-slate-900">Execute Refund</span>
                </button>
                <button onclick="advanceStep('close')" class="p-3 rounded-xl border border-slate-200 hover:border-slate-500 hover:bg-slate-100 text-left transition group">
                    <span class="block text-[10px] font-bold text-slate-400">STEP 6</span>
                    <span class="text-xs font-bold text-slate-900">Reconcile & Close</span>
                </button>
            </div>

            <!-- Simulation Terminal Log -->
            <div class="bg-slate-950 text-slate-200 rounded-xl p-4 font-mono text-xs overflow-x-auto shadow-inner">
                <div class="text-slate-500 mb-2">// Sandbox Real-Time Execution Log</div>
                <div id="sandbox-log" class="space-y-1">
                    <div class="text-emerald-400">✓ Database models initialized. SQLite test container active.</div>
                    <div class="text-slate-300">&gt; Ready for interactive simulation clicks above.</div>
                </div>
            </div>
        </div>

        <!-- 1-Click Demo Accounts Directory -->
        <div class="mb-12">
            <h2 class="text-xl font-black text-slate-900 mb-6 text-center">1-Click Fast Login Demo User Directory</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($demoAccounts as $account)
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                    {{ $account['badge'] }}
                                </span>
                                <span class="text-[10px] font-mono text-slate-400">Pass: password</span>
                            </div>

                            <h4 class="font-bold text-sm text-slate-900 mb-1">{{ $account['role'] }}</h4>
                            <div class="text-xs font-mono text-indigo-600 mb-3 break-all">{{ $account['email'] }}</div>
                            <p class="text-[11px] text-slate-500 mb-4">{{ $account['panel'] }}</p>
                        </div>

                        <form action="{{ route('demo.quick_login') }}" method="POST">
                            @csrf
                            <input type="hidden" name="email" value="{{ $account['email'] }}">
                            <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-indigo-600 text-white rounded-xl text-xs font-bold shadow transition flex items-center justify-center gap-1.5">
                                <span>Instant Log In</span> &rarr;
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    function advanceStep(stepName) {
        const logBox = document.getElementById('sandbox-log');
        const badge = document.getElementById('display-status-badge');

        fetch("{{ route('demo.step_advance') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ step: stepName })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                badge.innerText = data.status;
                const newLog = document.createElement('div');
                newLog.className = 'text-indigo-400';
                newLog.innerHTML = `&gt; [${new Date().toLocaleTimeString()}] ${data.message} &rarr; Status now: <span class="text-emerald-400 font-bold">${data.status}</span>`;
                logBox.appendChild(newLog);
            }
        })
        .catch(err => {
            console.error(err);
        });
    }
</script>
@endsection
