<x-form-section submit="updateProfileInformation">
    <x-slot name="title">{{ __('Profile Information') }}</x-slot>
    <x-slot name="description">{{ __('Update your account\'s profile information and email address.') }}</x-slot>

    <x-slot name="form">

        {{-- Profile Photo --}}
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
        <div class="col-span-6 sm:col-span-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Photo</label>

            {{-- Hidden real file input (wire:model needs it but we hide it visually) --}}
            <input type="file" id="photo-upload"
                   wire:model.live="photo"
                   accept="image/*"
                   style="position:absolute; width:1px; height:1px; opacity:0; pointer-events:none; overflow:hidden;"
                   onchange="
                       const reader = new FileReader();
                       reader.onload = e => {
                           document.getElementById('photo-img').src = e.target.result;
                           document.getElementById('photo-img').style.display = 'block';
                           document.getElementById('photo-initials').style.display = 'none';
                       };
                       reader.readAsDataURL(this.files[0]);
                   " />

            <div style="display:flex; align-items:center; gap:16px;">

                {{-- Avatar circle --}}
                <div style="width:80px; height:80px; border-radius:50%; overflow:hidden;
                             border:3px solid #86efac; flex-shrink:0; background:#16a34a;
                             display:flex; align-items:center; justify-content:center;">
                    @if(auth()->user()->profile_photo_path)
                        <img id="photo-img"
                             src="{{ Storage::disk('public')->url(auth()->user()->profile_photo_path) }}"
                             alt="Profile photo"
                             style="display:block; width:100%; height:100%; object-fit:cover;" />
                        <span id="photo-initials"
                              style="display:none; color:white; font-size:1.75rem; font-weight:700;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    @else
                        <img id="photo-img" src="" alt=""
                             style="display:none; width:100%; height:100%; object-fit:cover;" />
                        <span id="photo-initials"
                              style="display:block; color:white; font-size:1.75rem; font-weight:700;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    @endif
                </div>

                {{-- Action buttons --}}
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <button type="button"
                            onclick="document.getElementById('photo-upload').click()"
                            style="padding:7px 16px; font-size:13px; font-weight:500;
                                   border:1px solid #d1d5db; border-radius:8px;
                                   background:white; cursor:pointer; color:#374151;
                                   display:inline-flex; align-items:center; gap:6px;"
                            onmouseover="this.style.background='#f3f4f6'"
                            onmouseout="this.style.background='white'">
                        📷 Select A New Photo
                    </button>

                    @if(auth()->user()->profile_photo_path)
                    <button type="button"
                            wire:click="deleteProfilePhoto"
                            style="padding:7px 16px; font-size:13px; font-weight:500;
                                   border:1px solid #fca5a5; border-radius:8px;
                                   background:white; cursor:pointer; color:#dc2626;
                                   display:inline-flex; align-items:center; gap:6px;"
                            onmouseover="this.style.background='#fef2f2'"
                            onmouseout="this.style.background='white'">
                        🗑 Remove Photo
                    </button>
                    @endif
                </div>
            </div>

            {{-- Upload progress --}}
            <div wire:loading wire:target="photo"
                 style="margin-top:8px; font-size:12px; color:#16a34a;">
                ⏳ Uploading photo...
            </div>

            @error('photo')
                <p style="color:#ef4444; font-size:12px; margin-top:6px;">{{ $message }}</p>
            @enderror
        </div>
        @endif

        {{-- Name --}}
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full"
                     wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        {{-- Email --}}
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            {{-- Hidden username field — fixes browser accessibility warning --}}
            <input type="text" autocomplete="username"
                   style="position:absolute; opacity:0; pointer-events:none; width:1px; height:1px;"
                   value="{{ auth()->user()->email }}" readonly />
            <x-input id="email" type="email" class="mt-1 block w-full"
                     wire:model="state.email" required autocomplete="email" />
            <x-input-error for="email" class="mt-2" />
        </div>

        {{-- Currency Selector --}}
<div class="col-span-6 sm:col-span-4">
    <x-label for="currency" value="{{ __('Currency') }}" />

    <select id="currency"
            name="currency"
            wire:model="state.currency"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm
                   focus:ring-green-500 focus:border-green-500">

        <option value="USD">🇺🇸 USD - US Dollar ($)</option>
        <option value="GBP">🇬🇧 GBP - British Pound (£)</option>
        <option value="EUR">🇪🇺 EUR - Euro (€)</option>
        <option value="INR">🇮🇳 INR - Indian Rupee (₹)</option>
        <option value="LKR">🇱🇰 LKR - Sri Lankan Rupee (Rs)</option>

    </select>
</div>



    </x-slot>
    <x-slot name="actions">
        {{-- Saved confirmation --}}
        <x-action-message class="me-3" on="saved">
            <span style="color:#16a34a; font-weight:600; font-size:14px;">✅ Saved!</span>
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            <span wire:loading.remove wire:target="updateProfileInformation">Save</span>
            <span wire:loading wire:target="updateProfileInformation">Saving...</span>
        </x-button>
    </x-slot>

</x-form-section>
