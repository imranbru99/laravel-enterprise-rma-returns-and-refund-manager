@extends('layouts.marketing')

@section('title', 'Contact Us & Request an Enterprise Demo — Returns & Refund Manager')

@section('content')
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-600">Get in Touch</span>
            <h1 class="text-3xl font-black text-slate-900 mt-2 mb-3">Speak with our Solutions Team</h1>
            <p class="text-xs text-slate-600">
                Have questions about multi-branch reverse logistics, custom ERP integrations, or high-volume enterprise pricing? We are here to help.
            </p>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            <form action="{{ route('contact') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Your Full Name</label>
                        <input type="text" name="name" required class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500" placeholder="Jane Doe" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Work Email</label>
                        <input type="email" name="email" required class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500" placeholder="jane@company.com" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Company / Organization</label>
                        <input type="text" name="company" class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500" placeholder="Acme Retail Ltd" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Estimated Monthly Returns Volume</label>
                        <select name="volume" class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500">
                            <option>100 - 500 returns/month</option>
                            <option>500 - 2,500 returns/month</option>
                            <option>2,500 - 10,000 returns/month</option>
                            <option>10,000+ enterprise volume</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">How can we help your business?</label>
                    <textarea name="message" rows="4" required class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500" placeholder="Tell us about your branches, current challenges, and requirements..."></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                    Send Inquiry & Request Guided Walkthrough &rarr;
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
