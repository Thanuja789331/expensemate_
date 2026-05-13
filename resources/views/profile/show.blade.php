@php use Illuminate\Support\Facades\Storage; @endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseMate — Profile</title>
    <script>
        (function () {
            const themePreference = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const initialTheme = themePreference === 'dark' || (!themePreference && prefersDark) ? 'dark' : 'light';
            document.documentElement.classList.toggle('dark', initialTheme === 'dark');
            document.documentElement.style.colorScheme = initialTheme === 'dark' ? 'dark' : 'light';
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300">

    {{-- Navbar --}}
    <nav class="bg-green-800 text-white shadow-lg px-6 py-3
                flex justify-between items-center">
        <a href="{{ route('dashboard') }}"
           class="text-xl font-bold">
            Expense<span class="text-green-300">Mate</span>
        </a>
        <div class="flex gap-4 items-center">
            <a href="{{ route('dashboard') }}"
               class="hover:text-green-300 text-sm transition-colors duration-300">
                Dashboard
            </a>
            <a href="{{ route('expenses.index') }}"
               class="hover:text-green-300 text-sm transition-colors duration-300">
                Expenses
            </a>
            <a href="{{ route('summary') }}"
               class="hover:text-green-300 text-sm transition-colors duration-300">
                Summary
            </a>
            <x-theme-toggle />
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="hover:text-green-300 text-sm transition-colors duration-300">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div id="successMsg"
         class="max-w-4xl mx-auto mt-4 px-4">
        <div class="bg-green-100 border border-green-400
                    text-green-800 px-4 py-3 rounded dark:bg-green-900 dark:border-green-700 dark:text-green-100">
            ✅ {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="max-w-4xl mx-auto py-6 px-4">

        {{-- Profile Header --}}
        <div class="bg-green-700 rounded-xl p-6 mb-6 text-white">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-full border-4 border-white
                            flex-shrink-0 overflow-hidden">
                    @if(auth()->user()->profile_photo_path)
                        <img src="{{ Storage::disk('public')->url(
                                     auth()->user()->profile_photo_path) }}"
                             alt="Profile Photo"
                             class="w-full h-full object-cover" />
                    @else
                        <div class="w-full h-full bg-green-500
                                    flex items-center justify-center">
                            <span class="text-3xl font-bold">
                                {{ strtoupper(substr(
                                    auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
                <div>
                    <h2 class="text-2xl font-bold">
                        {{ auth()->user()->name }}
                    </h2>
                    <p class="text-green-200">
                        {{ auth()->user()->email }}
                    </p>
                    <span class="bg-green-600 text-white text-xs
                                 px-2 py-1 rounded-full mt-1
                                 inline-block">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Update Profile Information --}}
        <div class="bg-white dark:bg-slate-950 rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-700 dark:text-slate-100 mb-4
                       pb-2 border-b border-gray-200 dark:border-slate-700">
                👤 Profile Information
            </h3>
            @livewire('profile.update-profile-information-form')
        </div>

        {{-- Update Password --}}
        <div class="bg-white dark:bg-slate-950 rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-700 dark:text-slate-100 mb-4
                       pb-2 border-b border-gray-200 dark:border-slate-700">
                🔒 Change Password
            </h3>
            @livewire('profile.update-password-form')
        </div>

        {{-- Two Factor Authentication --}}
        <div class="bg-white dark:bg-slate-950 rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-700 dark:text-slate-100 mb-4
                       pb-2 border-b border-gray-200 dark:border-slate-700">
                🛡️ Two Factor Authentication
            </h3>
            @if(auth()->user()->two_factor_secret)
                <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700
                            rounded-lg p-4 mb-4">
                    <p class="text-green-700 dark:text-green-200 font-bold">
                        ✅ 2FA is ENABLED!
                    </p>
                </div>
                <form method="POST"
                      action="{{ route('2fa.disable') }}">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Disable 2FA?')"
                            class="bg-red-500 text-white px-6 py-2
                                   rounded-lg hover:bg-red-600">
                        🔓 Disable 2FA
                    </button>
                </form>
            @else
                <div class="bg-gray-50 dark:bg-slate-900 rounded-lg p-4 mb-4">
                    <p class="text-gray-700 dark:text-slate-300">
                        2FA is not enabled yet.
                    </p>
                </div>
                <a href="{{ route('2fa.enable') }}"
                   class="bg-green-700 text-white px-6 py-2
                          rounded-lg hover:bg-green-800
                          inline-block">
                    🛡️ Enable 2FA
                </a>
            @endif
        </div>

        {{-- Browser Sessions --}}
        <div class="bg-white dark:bg-slate-950 rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-700 dark:text-slate-100 mb-4
                       pb-2 border-b border-gray-200 dark:border-slate-700">
                🖥️ Browser Sessions
            </h3>
            @livewire('profile.logout-other-browser-sessions-form')
        </div>

        {{-- Delete Account --}}
        <div class="bg-white dark:bg-slate-950 rounded-xl shadow p-6 mb-6
                    border border-red-200 dark:border-red-700">
            <h3 class="text-lg font-bold text-red-600 dark:text-red-300 mb-4
                       pb-2 border-b border-red-200 dark:border-red-700">
                ⚠️ Delete Account
            </h3>
            @livewire('profile.delete-user-form')
        </div>

        {{-- Subscription Plan --}}
        <div class="bg-white dark:bg-slate-950 rounded-xl shadow p-6 mb-6
                    border border-green-200 dark:border-green-700">
            <h3 class="text-lg font-bold text-green-700 dark:text-green-300 mb-4
                       pb-2 border-b border-green-200 dark:border-green-700">
                💎 Current Plan
            </h3>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <span class="bg-green-100 text-green-700
                                 px-3 py-1 rounded-full
                                 font-bold text-sm dark:bg-green-900 dark:text-green-200">
                        FREE PLAN
                    </span>
                    <p class="text-gray-700 dark:text-slate-400 text-sm mt-2">
                        You are currently on the free plan
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 mb-4">
                <div class="text-sm text-gray-700 dark:text-slate-300">
                    ✅ Unlimited expense tracking
                </div>
                <div class="text-sm text-gray-700 dark:text-slate-300">
                    ✅ Visual analytics charts
                </div>
                <div class="text-sm text-gray-700 dark:text-slate-300">
                    ✅ API access
                </div>
                <div class="text-sm text-gray-700 dark:text-slate-300">
                    ✅ 2FA security
                </div>
                <div class="text-sm text-gray-700 dark:text-slate-300">
                    ✅ Google Sign-In
                </div>
                <div class="text-sm text-gray-700 dark:text-slate-300">
                    ✅ Admin panel access
                </div>
            </div>
        </div>

    </div>

    @livewireScripts
</body>
</html>
