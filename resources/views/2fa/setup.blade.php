@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="bg-green-700 rounded-xl p-6 mb-6 text-white text-center">
        <h1 class="text-2xl font-bold mb-2">
            🛡️ Setup Two Factor Authentication
        </h1>
        <p class="text-green-200">
            Secure your account with Google Authenticator
        </p>
    </div>

    {{-- Steps --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h3 class="font-bold text-gray-700 mb-4">
            📋 Follow these steps:
        </h3>
        <div class="space-y-4">
            <div class="flex items-start gap-3">
                <span class="bg-green-700 text-white w-8 h-8
                             rounded-full flex items-center
                             justify-center font-bold flex-shrink-0">
                    1
                </span>
                <div>
                    <p class="font-medium text-gray-700">
                        Download Google Authenticator
                    </p>
                    <p class="text-gray-500 text-sm">
                        Available on Google Play Store and Apple App Store
                    </p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="bg-green-700 text-white w-8 h-8
                             rounded-full flex items-center
                             justify-center font-bold flex-shrink-0">
                    2
                </span>
                <div>
                    <p class="font-medium text-gray-700">
                        Scan QR Code or Enter Key Manually
                    </p>
                    <p class="text-gray-500 text-sm">
                        Open app → tap + → Scan QR or Enter key
                    </p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="bg-green-700 text-white w-8 h-8
                             rounded-full flex items-center
                             justify-center font-bold flex-shrink-0">
                    3
                </span>
                <div>
                    <p class="font-medium text-gray-700">
                        Enter 6-digit code below
                    </p>
                    <p class="text-gray-500 text-sm">
                        Enter the code shown in Google Authenticator
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- QR Code Section --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h3 class="font-bold text-gray-700 mb-4 text-center">
            📱 Scan QR Code:
        </h3>

        {{-- QR Code Image --}}
        <div class="flex justify-center mb-6">
            <div class="bg-white p-3 border-4 border-green-700
                        rounded-xl inline-block">
                <img src="https://quickchart.io/qr?text={{ urlencode($qrCodeUrl) }}&size=200"
                     alt="2FA QR Code"
                     class="w-48 h-48" />
            </div>
        </div>

        {{-- Divider --}}
        <div class="flex items-center gap-3 mb-4">
            <hr class="flex-1 border-gray-200">
            <span class="text-gray-400 text-sm font-medium">
                OR ENTER MANUALLY
            </span>
            <hr class="flex-1 border-gray-200">
        </div>

        {{-- Manual Key --}}
        <div class="bg-yellow-50 border border-yellow-200
                    rounded-xl p-4 mb-2">
            <p class="text-yellow-700 font-bold mb-2 text-center">
                🔑 Manual Setup Key:
            </p>
            <p class="font-mono font-bold text-green-700
                      text-xl tracking-widest text-center
                      bg-white rounded-lg p-3 border
                      border-yellow-200">
                {{ $secret }}
            </p>
            <p class="text-yellow-600 text-xs mt-2 text-center">
                In Google Authenticator: tap + → Enter a setup key → paste above key
            </p>
        </div>
    </div>

    {{-- Verify Form --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h3 class="font-bold text-gray-700 mb-4">
            ✅ Verify Code:
        </h3>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400
                        text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('2fa.confirm') }}">
            @csrf

            <div class="mb-6">
                <label class="block text-gray-600 font-medium mb-2">
                    Enter 6-digit code from Google Authenticator:
                </label>
                <input type="text"
                       name="code"
                       maxlength="6"
                       placeholder="000000"
                       autofocus
                       class="w-full border-2 rounded-xl px-4 py-4
                              focus:outline-none focus:border-green-500
                              text-center text-3xl tracking-widest
                              font-mono font-bold
                              @error('code') border-red-400 @enderror" />
                @error('code')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex flex-col md:flex-row gap-3">
                <button type="submit"
                        class="bg-green-700 text-white px-8 py-3
                               rounded-xl hover:bg-green-800
                               font-semibold text-lg w-full md:w-auto">
                    ✅ Confirm & Enable 2FA
                </button>
                <a href="{{ route('profile.show') }}"
                   class="bg-gray-300 text-gray-700 px-8 py-3
                          rounded-xl hover:bg-gray-400 text-center
                          font-semibold w-full md:w-auto">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- Info Box --}}
    <div class="bg-blue-50 border border-blue-200
                rounded-xl p-4 mb-6">
        <h4 class="font-bold text-blue-700 mb-2">
            💡 What is Two Factor Authentication?
        </h4>
        <p class="text-blue-600 text-sm">
            2FA adds an extra layer of security to your account.
            Even if someone knows your password, they still need
            your phone to login. This makes your account much
            more secure!
        </p>
    </div>

</div>

@endsection
