<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseMate - {{ $title ?? 'Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @livewireStyles
</head>
<body class="bg-gray-100">

    {{-- Navbar --}}
    <nav class="bg-green-800 text-white shadow-lg">
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

                        {{-- Profile Dropdown --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                    class="flex items-center gap-2
                                           hover:text-green-300">
                                <div class="w-8 h-8 bg-green-600 rounded-full
                                            flex items-center justify-center
                                            font-bold">
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
                            <div x-show="open"
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48
                                        bg-white rounded-lg shadow-lg
                                        py-2 z-50">
                                <a href="{{ route('profile.show') }}"
                                   class="block px-4 py-2 text-gray-700
                                          hover:bg-green-50
                                          hover:text-green-700">
                                    👤 My Profile
                                </a>
                                <hr class="my-1">
                                <form method="POST"
                                      action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full text-left px-4 py-2
                                                   text-red-600 hover:bg-red-50">
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

    {{-- Flash Messages --}}
    <div class="max-w-6xl mx-auto mt-4 px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400
                        text-green-800 px-4 py-3 rounded mb-4">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400
                        text-red-800 px-4 py-3 rounded mb-4">
                ❌ {{ session('error') }}
            </div>
        @endif
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
    </script>

    @livewireScripts
</body>
</html>
