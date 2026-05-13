<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ExpenseMate - {{ $title ?? 'Dashboard' }}</title>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300">

    {{-- Navbar --}}
    <nav class="bg-green-800 dark:bg-green-900 text-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center py-3">

                {{-- Logo --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-2">
                    <div class="bg-white rounded-full p-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-7 w-7 text-green-700"
                             viewBox="0 0 24 24"
                             fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10
                                     10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2z
                                     m0-4h-2V7h2v6z"/>
                            <path d="M11.5 2C6.25 2 2 6.25 2 11.5S6.25 21
                                     11.5 21 21 16.75 21 11.5 2 16.75 2 11.5z
                                     M12 16l-4-4h3V8h2v4h3l-4 4z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-wide">
                            Expense<span class="text-green-300">Mate</span>
                        </span>
                        <p class="text-xs text-green-300 leading-none">
                            Smart Spending. Simple Tracking.
                        </p>
                    </div>
                </a>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex gap-6 items-center">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="hover:text-green-300 transition">
                            Dashboard
                        </a>
                        <a href="{{ route('expenses.index') }}"
                           class="hover:text-green-300 transition">
                            Expenses
                        </a>
                        <a href="{{ route('summary') }}"
                           class="hover:text-green-300 transition">
                            Summary
                        </a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="hover:text-green-300 transition">
                                Admin
                            </a>
                        @endif
                        <x-theme-toggle />

                        {{-- Profile Dropdown --}}
                        <div class="relative" data-dropdown>
                            <button type="button"
                                    data-dropdown-trigger
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    class="flex items-center gap-2 hover:text-green-300">
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span>{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div data-dropdown-menu
                                 class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-lg shadow-lg py-2 z-50">
                                <a href="{{ route('profile.show') }}"
                                   class="block px-4 py-2 text-gray-700 dark:text-slate-100 hover:bg-green-50 dark:hover:bg-slate-700 hover:text-green-700 dark:hover:text-green-200">
                                    👤 My Profile
                                </a>
                                <hr class="my-1 border-slate-200 dark:border-slate-700">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 dark:hover:text-red-200">
                                        🚪 Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="hover:text-green-300 transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="bg-white text-green-700 px-4 py-2
                                  rounded-lg hover:bg-green-100 font-medium">
                            Register
                        </a>
                    @endauth
                </div>

                {{-- Mobile Hamburger Button --}}
                <button id="menuBtn"
                        class="md:hidden focus:outline-none"
                        onclick="toggleMenu()">
                    <svg class="w-7 h-7" fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path id="menuIcon"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

            </div>

            {{-- Mobile Menu --}}
            <div id="mobileMenu" class="hidden md:hidden pb-4">
                <div class="flex items-center justify-between px-4 pb-4 border-b border-white/10">
                    <span class="text-sm text-slate-900 dark:text-slate-100">Theme</span>
                    <x-theme-toggle />
                </div>
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="block py-2 hover:text-green-300">
                        Dashboard
                    </a>
                    <a href="{{ route('expenses.index') }}"
                       class="block py-2 hover:text-green-300">
                        Expenses
                    </a>
                    <a href="{{ route('summary') }}"
                       class="block py-2 hover:text-green-300">
                        Summary
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="block py-2 hover:text-green-300">
                            Admin
                        </a>
                    @endif
                    <a href="{{ route('profile.show') }}"
                       class="block py-2 hover:text-green-300">
                        👤 My Profile
                    </a>
                    <form method="POST"
                          action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="block py-2 text-red-300
                                       hover:text-red-100">
                            🚪 Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="block py-2 hover:text-green-300">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="block py-2 hover:text-green-300">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Toast Notifications --}}
    <div x-data="toastComponent()"
         x-init="init()"
         class="fixed inset-x-0 top-4 z-50 flex justify-center px-4 pointer-events-none">
        <div class="w-full max-w-lg space-y-3">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="toast.visible"
                     x-transition.opacity.duration.300ms
                     class="pointer-events-auto rounded-xl border px-4 py-3 shadow-lg flex items-start gap-3"
                     :class="toast.type === 'success'
                         ? 'bg-green-50 border-green-400 text-green-800 dark:bg-green-900/90 dark:border-green-700 dark:text-green-100'
                         : 'bg-red-50 border-red-400 text-red-800 dark:bg-red-900/90 dark:border-red-700 dark:text-red-100'">
                    <div class="mt-0.5 text-lg" x-text="toast.type === 'success' ? '✅' : '❌'"></div>
                    <div class="grow text-sm leading-6" x-text="toast.message"></div>
                    <button type="button"
                            @click="remove(toast.id)"
                            class="text-current opacity-70 hover:opacity-100 transition">
                        ✕
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- Page Content --}}
    <main class="max-w-6xl mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- Mobile Menu Script --}}
    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        function toastComponent() {
            return {
                toasts: [],
                init() {
                    @if(session('success')) this.add('success', @json(session('success'))); @endif
                    @if(session('error')) this.add('error', @json(session('error'))); @endif
                    window.addEventListener('toast', event => {
                        if (event?.detail?.type && event?.detail?.message) {
                            this.add(event.detail.type, event.detail.message);
                        }
                    });
                },
                add(type, message) {
                    const id = Date.now() + Math.random();
                    this.toasts.push({ id, type, message, visible: true });
                    setTimeout(() => this.remove(id), 3000);
                },
                remove(id) {
                    const toast = this.toasts.find(item => item.id === id);
                    if (toast) {
                        toast.visible = false;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(item => item.id !== id);
                        }, 300);
                    }
                },
            };
        }
    </script>

    @livewireScripts
</body>
</html>
