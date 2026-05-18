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

    @vite(['resources/css/app.css'])
    @livewireStyles

    <style>
        [data-js-button], .jetstream-btn {
            display: inline-flex; align-items: center; padding: 0.5rem 1rem;
            background-color: #1B6B4A; color: white; border-radius: 0.5rem;
            font-size: 0.875rem; font-weight: 500; cursor: pointer; border: none;
            transition: background-color 0.15s;
        }
        [data-js-button]:hover { background-color: #155a3c; }
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.5);
            z-index: 50; display: flex; align-items: center; justify-content: center;
        }
        .modal-box {
            background: white; border-radius: 1rem; padding: 1.5rem;
            width: 100%; max-width: 32rem; box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .dark .modal-box { background: #1e293b; color: #f1f5f9; }
    </style>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300">

   {{-- ===== NAVBAR (matches main layout exactly) ===== --}}
<nav class="bg-green-800 text-white shadow-lg">
    <div style="max-width:1152px; margin:0 auto; padding:0 16px;">
        <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0;">

            {{-- Logo --}}
            <a href="{{ route('dashboard') }}"
               style="display:flex; align-items:center; gap:8px; text-decoration:none; color:white;">
                <div style="background:white; border-radius:50%; padding:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:28px; height:28px; color:#15803d;"
                         viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                    </svg>
                </div>
                <div>
                    <div style="font-size:20px; font-weight:700; letter-spacing:0.02em;">
                        Expense<span style="color:#86efac;">Mate</span>
                    </div>
                    <div style="font-size:11px; color:#86efac; line-height:1;">
                        Smart Spending. Simple Tracking.
                    </div>
                </div>
            </a>

            {{-- Desktop links --}}
            <div id="desktop-nav"
                 style="display:flex; align-items:center; gap:24px; font-size:14px;">
                <a href="{{ route('dashboard') }}"
                   style="color:white; text-decoration:none;"
                   onmouseover="this.style.color='#86efac'"
                   onmouseout="this.style.color='white'">Dashboard</a>
                <a href="{{ route('expenses.index') }}"
                   style="color:white; text-decoration:none;"
                   onmouseover="this.style.color='#86efac'"
                   onmouseout="this.style.color='white'">Expenses</a>
                <a href="{{ route('summary') }}"
                   style="color:white; text-decoration:none;"
                   onmouseover="this.style.color='#86efac'"
                   onmouseout="this.style.color='white'">Summary</a>
                <a href="{{ route('budget.index') }}"
                   style="color:white; text-decoration:none;"
                   onmouseover="this.style.color='#86efac'"
                   onmouseout="this.style.color='white'"> Budget</a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                   style="color:white; text-decoration:none;"
                   onmouseover="this.style.color='#86efac'"
                   onmouseout="this.style.color='white'">Admin</a>
                @endif

                {{-- Theme toggle --}}
                <button onclick="toggleTheme()"
                        style="background:none; border:none; color:white; cursor:pointer; padding:4px;"
                        title="Toggle theme">
                    <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 000 10A5 5 0 0012 7z"/>
                    </svg>
                </button>

                {{-- Profile dropdown --}}
                <div style="position:relative;">
                    <button onclick="toggleProfileDropdown()"
                            style="display:flex; align-items:center; gap:8px; background:none;
                                   border:none; color:white; cursor:pointer; font-size:14px;">
                        <div style="width:32px; height:32px; background:#16a34a; border-radius:50%;
                                    display:flex; align-items:center; justify-content:center; font-weight:700;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span>{{ auth()->user()->name }}</span>
                        <svg style="width:16px; height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown --}}
                    <div id="profile-dropdown"
                         style="display:none; position:absolute; right:0; top:calc(100% + 8px);
                                width:180px; background:white; border-radius:10px;
                                box-shadow:0 8px 24px rgba(0,0,0,0.15); padding:8px 0; z-index:100;">
                        <a href="{{ route('profile.show') }}"
                           style="display:block; padding:10px 16px; font-size:13px;
                                  color:#374151; text-decoration:none;"
                           onmouseover="this.style.background='#f0fdf4'; this.style.color='#15803d'"
                           onmouseout="this.style.background='none'; this.style.color='#374151'">
                            👤 My Profile
                        </a>
                        <hr style="margin:4px 0; border:none; border-top:1px solid #f1f5f9;">
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit"
                                    style="width:100%; text-align:left; padding:10px 16px;
                                           font-size:13px; color:#dc2626; background:none;
                                           border:none; cursor:pointer;"
                                    onmouseover="this.style.background='#fef2f2'"
                                    onmouseout="this.style.background='none'">
                                🚪 Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Hamburger --}}
            <button id="hamburger-btn"
                    onclick="toggleMobileMenu()"
                    style="display:none; background:none; border:none;
                           color:white; cursor:pointer; padding:4px;">
                <svg style="width:26px; height:26px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu"
             style="display:none; border-top:1px solid rgba(255,255,255,0.15);
                    padding:8px 0 16px;">
            <a href="{{ route('dashboard') }}"
               style="display:block; color:white; text-decoration:none;
                      padding:10px 12px; border-radius:8px; font-size:14px;"
               onmouseover="this.style.background='rgba(255,255,255,0.1)'"
               onmouseout="this.style.background='transparent'">📊 Dashboard</a>
            <a href="{{ route('expenses.index') }}"
               style="display:block; color:white; text-decoration:none;
                      padding:10px 12px; border-radius:8px; font-size:14px;"
               onmouseover="this.style.background='rgba(255,255,255,0.1)'"
               onmouseout="this.style.background='transparent'">💸 Expenses</a>
            <a href="{{ route('summary') }}"
               style="display:block; color:white; text-decoration:none;
                      padding:10px 12px; border-radius:8px; font-size:14px;"
               onmouseover="this.style.background='rgba(255,255,255,0.1)'"
               onmouseout="this.style.background='transparent'">📈 Summary</a>
            <a href="{{ route('budget.index') }}"
               style="display:block; color:white; text-decoration:none;
                      padding:10px 12px; border-radius:8px; font-size:14px;"
               onmouseover="this.style.background='rgba(255,255,255,0.1)'"
               onmouseout="this.style.background='transparent'">💰 Budget</a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
               style="display:block; color:white; text-decoration:none;
                      padding:10px 12px; border-radius:8px; font-size:14px;"
               onmouseover="this.style.background='rgba(255,255,255,0.1)'"
               onmouseout="this.style.background='transparent'">🛡️ Admin</a>
            @endif
            <a href="{{ route('profile.show') }}"
               style="display:block; color:white; text-decoration:none;
                      padding:10px 12px; border-radius:8px; font-size:14px;"
               onmouseover="this.style.background='rgba(255,255,255,0.1)'"
               onmouseout="this.style.background='transparent'">👤 My Profile</a>
            <button onclick="toggleTheme()"
                    style="display:block; width:100%; text-align:left; background:none;
                           border:none; color:white; padding:10px 12px; border-radius:8px;
                           font-size:14px; cursor:pointer;"
                    onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                    onmouseout="this.style.background='transparent'">🌙 Toggle Theme</button>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit"
                        style="display:block; width:100%; text-align:left; background:none;
                               border:none; color:#fca5a5; padding:10px 12px;
                               border-radius:8px; font-size:14px; cursor:pointer;"
                        onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                        onmouseout="this.style.background='transparent'">🚪 Logout</button>
            </form>
        </div>
    </div>
</nav>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="max-w-4xl mx-auto mt-4 px-4">
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded
                    dark:bg-green-900 dark:border-green-700 dark:text-green-100">
            ✅ {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="max-w-4xl mx-auto py-6 px-4">

        {{-- Profile Header --}}
        <div class="bg-green-700 rounded-xl p-6 mb-6 text-white">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-full border-4 border-white flex-shrink-0 overflow-hidden">
                    @if(auth()->user()->profile_photo_path)
                        <img src="{{ Storage::disk('public')->url(auth()->user()->profile_photo_path) }}"
                             alt="Profile Photo" class="w-full h-full object-cover" />
                    @else
                        <div class="w-full h-full bg-green-500 flex items-center justify-center">
                            <span class="text-3xl font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
                <div class="min-w-0">
                    <h2 class="text-2xl font-bold truncate">{{ auth()->user()->name }}</h2>
                    <p class="text-green-200 truncate">{{ auth()->user()->email }}</p>
                    <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full mt-1 inline-block">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Update Profile Information --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-700 dark:text-slate-100 mb-4 pb-2
                       border-b border-gray-200 dark:border-slate-700">
                👤 Profile Information
            </h3>
            @livewire('profile.update-profile-information-form')
        </div>

        {{-- Update Password --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-700 dark:text-slate-100 mb-4 pb-2
                       border-b border-gray-200 dark:border-slate-700">
                🔒 Change Password
            </h3>
            @livewire('profile.update-password-form')
        </div>

        {{-- Two Factor Authentication --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-700 dark:text-slate-100 mb-4 pb-2
                       border-b border-gray-200 dark:border-slate-700">
                🛡️ Two Factor Authentication
            </h3>
            @if(auth()->user()->two_factor_secret)
                <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700
                            rounded-lg p-4 mb-4">
                    <p class="text-green-700 dark:text-green-200 font-bold">✅ 2FA is ENABLED!</p>
                </div>
                <form method="POST" action="{{ route('2fa.disable') }}">
                    @csrf
                    <button type="submit" onclick="return confirm('Disable 2FA?')"
                            class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">
                        🔓 Disable 2FA
                    </button>
                </form>
            @else
                <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-4 mb-4">
                    <p class="text-gray-700 dark:text-slate-300">2FA is not enabled yet.</p>
                </div>
                <a href="{{ route('2fa.enable') }}"
                   class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 inline-block">
                    🛡️ Enable 2FA
                </a>
            @endif
        </div>

        {{-- Browser Sessions --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-700 dark:text-slate-100 mb-4 pb-2
                       border-b border-gray-200 dark:border-slate-700">
                🖥️ Browser Sessions
            </h3>
            @livewire('profile.logout-other-browser-sessions-form')
        </div>

        {{-- Delete Account --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow p-6 mb-6
                    border border-red-200 dark:border-red-700">
            <h3 class="text-lg font-bold text-red-600 dark:text-red-300 mb-4 pb-2
                       border-b border-red-200 dark:border-red-700">
                ⚠️ Delete Account
            </h3>
            @livewire('profile.delete-user-form')
        </div>

        {{-- Current Plan --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow p-6 mb-6
                    border border-green-200 dark:border-green-700">
            <h3 class="text-lg font-bold text-green-700 dark:text-green-300 mb-4 pb-2
                       border-b border-green-200 dark:border-green-700">
                💎 Current Plan
            </h3>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <span class="bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200
                                 px-3 py-1 rounded-full font-bold text-sm">
                        FREE PLAN
                    </span>
                    <p class="text-gray-600 dark:text-slate-400 text-sm mt-2">
                        You are currently on the free plan
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="text-sm text-gray-700 dark:text-slate-300">✅ Unlimited expense tracking</div>
                <div class="text-sm text-gray-700 dark:text-slate-300">✅ Visual analytics charts</div>
                <div class="text-sm text-gray-700 dark:text-slate-300">✅ API access</div>
                <div class="text-sm text-gray-700 dark:text-slate-300">✅ 2FA security</div>
                <div class="text-sm text-gray-700 dark:text-slate-300">✅ Google Sign-In</div>
                <div class="text-sm text-gray-700 dark:text-slate-300">✅ Admin panel access</div>
            </div>
        </div>

    </div>

    {{-- Toast --}}
    <div id="lw-toast"
         style="display:none; position:fixed; bottom:1.5rem; right:1.5rem; z-index:9999;
                background:#1B6B4A; color:white; padding:0.75rem 1.25rem; border-radius:0.75rem;
                font-size:0.875rem; font-weight:500; box-shadow:0 4px 20px rgba(0,0,0,0.2);">
        ✅ <span id="lw-toast-msg">Saved successfully!</span>
    </div>

    <script>
    function handleNav() {
        const hamburger = document.getElementById('hamburger-btn');
        const desktopNav = document.getElementById('desktop-nav');
        if (window.innerWidth < 768) {
            hamburger.style.display = 'block';
            desktopNav.style.display = 'none';
        } else {
            hamburger.style.display = 'none';
            desktopNav.style.display = 'flex';
            document.getElementById('mobile-menu').style.display = 'none';
        }
    }
    handleNav();
    window.addEventListener('resize', handleNav);

    function toggleMobileMenu() {
        const m = document.getElementById('mobile-menu');
        m.style.display = m.style.display === 'none' ? 'block' : 'none';
    }

    function toggleProfileDropdown() {
        const d = document.getElementById('profile-dropdown');
        d.style.display = d.style.display === 'none' ? 'block' : 'none';
    }

    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('profile-dropdown');
        if (dropdown && !e.target.closest('[onclick="toggleProfileDropdown()"]')) {
            dropdown.style.display = 'none';
        }
    });

    function toggleTheme() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        document.documentElement.style.colorScheme = isDark ? 'dark' : 'light';
    }

    function showToast(msg) {
        const el = document.getElementById('lw-toast');
        document.getElementById('lw-toast-msg').textContent = msg;
        el.style.display = 'block';
        setTimeout(() => el.style.display = 'none', 3000);
    }

    document.addEventListener('livewire:init', () => {
        Livewire.on('saved', () => showToast('Profile information saved!'));
        Livewire.on('password-updated', () => showToast('Password updated successfully!'));
        Livewire.on('loggedOut', () => showToast('Other sessions logged out!'));
    });
</script>

    @livewireScripts

</body>
</html>
