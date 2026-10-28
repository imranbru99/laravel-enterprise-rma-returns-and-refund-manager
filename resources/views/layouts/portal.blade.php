<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Returns Portal — Returns & Refund Manager')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    <!-- Customer Portal Navigation -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        R
                    </span>
                    <span class="font-bold text-slate-900 text-sm sm:text-base">
                        Returns & Refund <span class="text-indigo-600">Portal</span>
                    </span>
                </a>

                @auth
                    <nav class="hidden sm:flex items-center gap-4 text-xs font-semibold text-slate-600">
                        <a href="{{ route('portal.dashboard') }}" class="{{ request()->routeIs('portal.dashboard') ? 'text-indigo-600 font-bold' : 'hover:text-indigo-600' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('portal.orders') }}" class="{{ request()->routeIs('portal.orders') ? 'text-indigo-600 font-bold' : 'hover:text-indigo-600' }}">
                            My Orders & Returns
                        </a>
                    </nav>
                @endauth
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <span class="text-xs text-slate-600 hidden sm:inline">
                        Signed in as <strong>{{ auth()->user()->name }}</strong>
                    </span>
                    <form action="{{ route('portal.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-red-600 hover:underline">
                            Log out
                        </button>
                    </form>
                @else
                    <a href="{{ route('portal.guest') }}" class="text-xs font-semibold text-slate-600 hover:text-indigo-600">
                        Guest Lookup
                    </a>
                    <a href="{{ route('portal.login') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        Sign In
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold flex items-center justify-between">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="mt-20 border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} Returns & Refund Manager. Secure Self-Service Customer Experience.
    </footer>

</body>
</html>
