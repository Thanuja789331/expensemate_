<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseMate — Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100">

    {{-- Simple Navbar --}}
    <nav class="bg-green-800 text-white shadow-lg px-6 py-3
                flex justify-between items-center">
        <a href="{{ route('dashboard') }}"
           class="text-xl font-bold">
            Expense<span class="text-green-300">Mate</span>
        </a>
        <div class="flex gap-4 items-center">
            <a href="{{ route('dashboard') }}"
               class="hover:text-green-300">
                Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="hover:text-green-300">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-6 px-4">

        {{-- Profile Header --}}
        <div class="bg-green-700 rounded-xl p-6 mb-6 text-white">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 bg-green-500 rounded-full
                            flex items-center justify-center
                            border-4 border-white">
                    <span class="text-3xl font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">
                        {{ auth()->user()->name }}
                    </h2>
                    <p class="text-green-200">
                        {{ auth()->user()->email }}
                    </p>
                    <span class="bg-green-600 text-white text-xs
                                 px-2 py-1 rounded-full mt-1 inline-block">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Update Profile Information --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-700 mb-4 pb-2
                       border-b border-gray-200">
                👤 Profile Information
            </h3>
            @livewire('profile.update-profile-information-form')
        </div>

        {{-- Update Password --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-700 mb-4 pb-2
                       border-b border-gray-200">
                🔒 Change Password
            </h3>
            @livewire('profile.update-password-form')
        </div>

        {{-- Two Factor Authentication --}}
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-700 mb-4 pb-2
               border-b border-gray-200">
        🛡️ Two Factor Authentication
    </h3>

    @if(auth()->user()->two_factor_secret)
        {{-- 2FA Enabled --}}
        <div class="bg-green-50 border border-green-200
                    rounded-lg p-4 mb-4">
            <p class="text-green-700 font-bold">
                ✅ Two Factor Authentication is ENABLED!
            </p>
            <p class="text-green-600 text-sm mt-1">
                Your account is protected with 2FA.
            </p>
        </div>
        <form method="POST" action="{{ route('2fa.disable') }}">
            @csrf
            <button type="submit"
                    onclick="return confirm('Disable 2FA?')"
                    class="bg-red-500 text-white px-6 py-2
                           rounded-lg hover:bg-red-600">
                🔓 Disable 2FA
            </button>
        </form>
    @else
        {{-- 2FA Disabled --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-4">
            <p class="text-gray-600">
                You have not enabled two factor authentication.
            </p>
            <p class="text-gray-500 text-sm mt-1">
                Enable 2FA to add extra security to your account.
            </p>
        </div>
        <a href="{{ route('2fa.enable') }}"
           class="bg-green-700 text-white px-6 py-2
                  rounded-lg hover:bg-green-800 inline-block">
            🛡️ Enable 2FA
        </a>
    @endif
</div>

        {{-- Browser Sessions --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-700 mb-4 pb-2
                       border-b border-gray-200">
                🖥️ Browser Sessions
            </h3>
            @livewire('profile.logout-other-browser-sessions-form')
        </div>

        {{-- Delete Account --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6
                    border border-red-200">
            <h3 class="text-lg font-bold text-red-600 mb-4 pb-2
                       border-b border-red-200">
                ⚠️ Delete Account
            </h3>
            @livewire('profile.delete-user-form')
        </div>

    </div>

    @livewireScripts
</body>
</html>
