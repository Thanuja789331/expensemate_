<div>
    {{-- Trigger Button --}}
    <p class="text-sm text-gray-600 dark:text-slate-400 mb-4">
        Once your account is deleted, all of its resources and data will be permanently deleted.
        Please download any data you wish to retain beforehand.
    </p>

    <button
        wire:click="confirmUserDeletion"
        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors"
    >
        Delete Account
    </button>

    {{-- Confirmation Modal (pure Livewire, no Alpine entangle) --}}
    @if($confirmingUserDeletion)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md p-6">

            {{-- Header --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/40 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Delete Account</h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400">This action cannot be undone.</p>
                </div>
            </div>

            {{-- Body --}}
            <p class="text-sm text-gray-600 dark:text-slate-300 mb-4">
                Are you sure you want to delete your account? All your data will be permanently removed.
                Please enter your password to confirm.
            </p>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">
                    Current Password
                </label>
                <input
                    type="password"
                    wire:model="password"
                    wire:keydown.enter="deleteUser"
                    placeholder="Enter your password"
                    class="w-full px-4 py-2 border border-gray-200 dark:border-slate-600 rounded-lg text-sm
                           bg-white dark:bg-slate-700 text-gray-900 dark:text-white
                           focus:outline-none focus:ring-2 focus:ring-red-500"
                    autofocus
                />
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-3">
                <button
                    wire:click="$set('confirmingUserDeletion', false)"
                    class="px-4 py-2 text-sm text-gray-600 dark:text-slate-300 bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors"
                >
                    Cancel
                </button>
                <button
                    wire:click="deleteUser"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="deleteUser">Delete My Account</span>
                    <span wire:loading wire:target="deleteUser">Deleting...</span>
                </button>
            </div>

        </div>
    </div>
    @endif
</div>
