<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Returns & Refund Manager — Enterprise RMA & Reverse Logistics SaaS')</title>
    <meta name="description" content="AI-Powered Enterprise Returns, Refund, Exchange, Reverse Logistics, Warranty & Repair Management Platform. Built for Retail, E-Commerce, 3PL & Manufacturers.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Global Top Alert Bar -->
    <div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-700 text-white text-xs font-semibold py-2 px-4 text-center">
        <span>🚀 Live Enterprise Returns Platform with Multi-Branch, AI-Classification & Sandbox Simulator.</span>
        <a href="{{ route('demo') }}" class="underline ml-2 hover:text-indigo-200">Try Interactive Demo Showcase &rarr;</a>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-md shadow-indigo-500/20">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </span>
                    <span class="font-extrabold text-xl tracking-tight text-slate-900">
                        Returns<span class="text-indigo-600">&</span>Refund<span class="text-purple-600">.Manager</span>
                    </span>
                </a>

                <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-600">
                    <a href="{{ route('features') }}" class="hover:text-indigo-600 transition">Features</a>
                    <a href="{{ route('workflow') }}" class="hover:text-indigo-600 transition">RMA Workflow</a>
                    <a href="{{ route('industries') }}" class="hover:text-indigo-600 transition">Industries</a>
                    <a href="{{ route('pricing') }}" class="hover:text-indigo-600 transition">Pricing</a>
                    <a href="{{ route('demo') }}" class="text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Live Demo
                    </a>
                    <a href="{{ route('docs') }}" class="hover:text-indigo-600 transition">Docs & API</a>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('portal.dashboard') }}" class="text-xs sm:text-sm font-semibold text-slate-700 hover:text-indigo-600 px-3 py-2">
                    Customer Portal
                </a>
                <a href="/admin" class="hidden sm:inline-flex text-xs sm:text-sm font-semibold text-slate-700 hover:text-indigo-600 px-3 py-2">
                    Staff Login
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/20 transition">
                    Start Free Trial
                </a>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 pt-16 pb-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">
                <div class="col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold">R</span>
                        <span class="font-extrabold text-lg text-white">Returns & Refund Manager</span>
                    </div>
                    <p class="text-xs text-slate-400 max-w-sm mb-4 leading-relaxed">
                        The world-class enterprise SaaS solution for Returns Merchandise Authorization (RMA), Automated Refunds, Variant Exchanges, Reverse Logistics, Multi-Branch Inspections, and AI Defect Classification.
                    </p>
                    <div class="text-xs text-slate-400">
                        Designed & Developed by <a href="https://imrandev.bd" target="_blank" class="text-indigo-400 hover:underline font-semibold">Imran Ahmed</a>.
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Product</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('features') }}" class="hover:text-white transition">90 SaaS Modules</a></li>
                        <li><a href="{{ route('workflow') }}" class="hover:text-white transition">RMA Lifecycle Engine</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-white transition">Pricing Plans</a></li>
                        <li><a href="{{ route('demo') }}" class="hover:text-white transition">Interactive Sandbox Demo</a></li>
                        <li><a href="/admin" class="hover:text-white transition">Filament Admin Command Center</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Solutions</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('industries') }}" class="hover:text-white transition">Omnichannel Retail Chains</a></li>
                        <li><a href="{{ route('industries') }}" class="hover:text-white transition">E-Commerce & Marketplaces</a></li>
                        <li><a href="{{ route('industries') }}" class="hover:text-white transition">3PL Reverse Logistics</a></li>
                        <li><a href="{{ route('industries') }}" class="hover:text-white transition">Electronics Service & Repairs</a></li>
                        <li><a href="{{ route('industries') }}" class="hover:text-white transition">Warranty Providers</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Developer & Legal</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('docs') }}" class="hover:text-white transition">API Documentation</a></li>
                        <li><a href="{{ route('portal.guest') }}" class="hover:text-white transition">Guest Return Lookup</a></li>
                        <li><a href="https://imrandev.bd" target="_blank" class="hover:text-white transition">Official Developer Site</a></li>
                        <li><span class="text-slate-400">me@imrandev.bd</span></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact & Support</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Returns & Refund Manager. Built with PHP Laravel & Filament.</p>
                <div class="flex gap-4 mt-4 sm:mt-0">
                    <a href="https://imrandev.bd" class="hover:text-slate-400">Author: Imran Ahmed</a>
                    <span>•</span>
                    <a href="{{ route('docs') }}" class="hover:text-slate-400">Documentation</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
