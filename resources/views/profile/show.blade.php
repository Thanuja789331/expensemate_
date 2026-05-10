@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Profile Header Card --}}
    <div class="bg-green-700 rounded-xl p-6 mb-6 text-white">
        <div class="flex items-center gap-4">
            {{-- Avatar --}}
            <div class="w-20 h-20 bg-white rounded-full
                        flex items-center justify-center flex-shrink-0">
                <span class="text-green-700 text-3xl font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
            </div>
            {{-- User Info --}}
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
            🛡️ Two Factor Authentication (2FA)
        </h3>
        @livewire('profile.two-factor-authentication-form')
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

@endsection
