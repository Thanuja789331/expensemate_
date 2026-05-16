<div>
    <p class="text-sm text-gray-600 dark:text-slate-400 mb-4">
        If necessary, you may log out of all other browser sessions across all your devices.
        Recent sessions are listed below.
    </p>

    {{-- Session List --}}
    @if(count($this->sessions) > 0)
    <div class="mb-5 space-y-3">
        @foreach($this->sessions as $session)
        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-800 rounded-lg">
            <div class="text-gray-500 dark:text-slate-400">
                @if($session->agent->isDesktop())
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                @else
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                @endif
            </div>
            <div class="flex-1">
                <div class="text-sm font-medium text-gray-700 dark:text-slate-200">
                    {{ $session->agent->platform() ?: 'Unknown' }} — {{ $session->agent->browser() ?: 'Unknown' }}
                </div>
                <div class="text-xs text-gray-500 dark:text-slate-400">
                    {{ $session->ip_address }} ·
                    @if($session->is_current_device)
                        <span class="text-green-600 dark:text-green-400 font-semibold">This device</span>
                    @else
                        Last active {{ $session->last_active }}
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="mb-5 p-3 bg-gray-50 dark:bg-slate-800 rounded-lg text-sm text-gray-500 dark:text-slate-400">
        No other active sessions found.
    </div>
    @endif

    {{-- Trigger Button --}}
    <button
        wire:click="confirmLogout"
        class="px-4 py-2 bg-[#1B6B4A] text-white text-sm font-medium rounded-lg hover:bg-[#155a3c] transition-colors"
    >
        Log Out Other Browser Sessions
    </button>

    {{-- Success message --}}
    @if(session()->has('loggedOut'))
    <p class="mt-3 text-sm text-green-600 dark:text-green-400 font-medium">✅ Done. Other sessions logged out.</p>
    @endif

    {{-- Confirmation Modal (pure Livewire) --}}
    @if($confirmingLogout)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md p-6">

            {{-- Header --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Log Out Other Sessions</h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400">Confirm with your password.</p>
                </div>
            </div>

            {{-- Body --}}
            <p class="text-sm text-gray-600 dark:text-slate-300 mb-4">
                Please enter your password to log out of all other browser sessions on your devices.
            </p>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">
                    Current Password
                </label>
                <input
                    type="password"
                    wire:model="password"
                    wire:keydown.enter="logoutOtherBrowserSessions"
                    placeholder="Enter your password"
                    class="w-full px-4 py-2 border border-gray-200 dark:border-slate-600 rounded-lg text-sm
                           bg-white dark:bg-slate-700 text-gray-900 dark:text-white
                           focus:outline-none focus:ring-2 focus:ring-green-500"
                    autofocus
                />
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-3">
                <button
                    wire:click="$set('confirmingLogout', false)"
                    class="px-4 py-2 text-sm text-gray-600 dark:text-slate-300 bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors"
                >
                    Cancel
                </button>
                <button
                    wire:click="logoutOtherBrowserSessions"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 text-sm text-white bg-[#1B6B4A] rounded-lg hover:bg-[#155a3c] transition-colors disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="logoutOtherBrowserSessions">Log Out Sessions</span>
                    <span wire:loading wire:target="logoutOtherBrowserSessions">Logging out...</span>
                </button>
            </div>

        </div>
    </div>
    @endif
</div>
