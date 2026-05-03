<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseMate - {{ $title ?? 'Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">

    {{-- Navbar --}}
    <nav class="bg-green-700 text-white px-6 py-4 flex justify-between items-center shadow">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold">
            💰 ExpenseMate
        </a>
        <div class="flex gap-4 items-center">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="hover:underline">Dashboard</a>
                <a href="{{ route('expenses.index') }}"
                   class="hover:underline">Expenses</a>
                <a href="{{ route('summary') }}"
                   class="hover:underline">Summary</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="hover:underline">Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="bg-white text-green-700 px-3 py-1 rounded hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:underline">Login</a>
                <a href="{{ route('register') }}"
                   class="bg-white text-green-700 px-3 py-1 rounded hover:bg-gray-100">
                    Register
                </a>
            @endauth
        </div>
    </nav>

    {{-- Success / Error Messages --}}
    <div class="max-w-6xl mx-auto mt-4 px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-800
                        px-4 py-3 rounded mb-4">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-800
                        px-4 py-3 rounded mb-4">
                ❌ {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Page Content --}}
    <main class="max-w-6xl mx-auto px-4 py-6">
        @yield('content')
    </main>

</body>
</html>
