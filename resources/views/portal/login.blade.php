@extends('layouts.portal')

@section('title', 'Sign In — Customer Returns Portal')

@section('content')
<div class="max-w-md mx-auto py-12">
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        <h2 class="text-xl font-black text-slate-900 mb-1">Customer Sign In</h2>
        <p class="text-xs text-slate-500 mb-6">Access your past orders, track return shipments, and request refunds.</p>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('portal.authenticate') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email', 'customer@retailsphere.demo.example') }}" required
                    class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500" />
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" value="password" required
                    class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500" />
            </div>

            <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow transition">
                Sign In to Portal &rarr;
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-100">
            <div class="text-xs text-slate-500 mb-3 text-center">Looking up a return without logging in?</div>
            <a href="{{ route('portal.guest') }}" class="block w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-center rounded-xl text-xs font-bold transition">
                Guest Return & Order Lookup &rarr;
            </a>
        </div>
    </div>
</div>
@endsection
