@extends('layouts.marketing')

@section('title', 'Start Your 14-Day Free Trial — Returns & Refund Manager')

@section('content')
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-600">Immediate Setup</span>
            <h1 class="text-3xl font-black text-slate-900 mt-2 mb-2">Create Your Organization</h1>
            <p class="text-xs text-slate-600">
                14-day full feature trial. Multi-branch, RMA engine, and AI classification ready in seconds.
            </p>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Company / Brand Name</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" required
                        class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500" placeholder="e.g. RetailSphere Global" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Your Full Name (Tenant Owner)</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500" placeholder="e.g. Alex Rahman" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Work Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500" placeholder="alex@retailsphere.com" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Password (min 8 characters)</label>
                    <input type="password" name="password" required
                        class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Select SaaS Plan</label>
                    <select name="plan_key" class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500">
                        @foreach($plans as $plan)
                            <option value="{{ $plan->key }}" {{ request('plan') === $plan->key ? 'selected' : '' }}>
                                {{ $plan->name }} (${{ number_format($plan->price_monthly, 0) }}/mo)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                        Launch Organization & Go To Admin Panel &rarr;
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-xs text-slate-500 border-t border-slate-100 pt-4">
                Already registered? <a href="/admin/login" class="text-indigo-600 font-bold hover:underline">Log in to Staff Admin</a>
            </div>
        </div>
    </div>
</section>
@endsection
