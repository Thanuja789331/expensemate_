<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseMate — Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex">

    {{-- Left Side — Branding --}}
    <div class="hidden md:flex md:w-1/2 bg-green-700
                flex-col justify-center items-center p-12 text-white">

        {{-- Logo --}}
        <div class="bg-white rounded-full p-4 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-16 w-16 text-green-700"
                 fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2
                         3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402
                         2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11
                         0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0
                         9 9 0 0118 0z"/>
            </svg>
        </div>

        {{-- Brand Name --}}
        <h1 class="text-4xl font-bold mb-2">
            Expense<span class="text-green-300">Mate</span>
        </h1>
        <p class="text-green-200 text-lg mb-12">
            Smart Spending. Simple Tracking.
        </p>

        {{-- Features --}}
        <div class="space-y-4 w-full max-w-sm">
            <div class="flex items-center gap-3 bg-green-600
                        rounded-lg p-3">
                <span class="text-2xl">📊</span>
                <div>
                    <p class="font-bold">Track Expenses</p>
                    <p class="text-green-200 text-sm">
                        Monitor all your spending
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 bg-green-600
                        rounded-lg p-3">
                <span class="text-2xl">📈</span>
                <div>
                    <p class="font-bold">Visual Analytics</p>
                    <p class="text-green-200 text-sm">
                        Charts and insights
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 bg-green-600
                        rounded-lg p-3">
                <span class="text-2xl">🔒</span>
                <div>
                    <p class="font-bold">Secure & Private</p>
                    <p class="text-green-200 text-sm">
                        Your data is protected
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Right Side — Login Form --}}
    <div class="w-full md:w-1/2 flex flex-col
                justify-center items-center p-8">

        {{-- Mobile Logo --}}
        <div class="md:hidden text-center mb-8">
            <h1 class="text-3xl font-bold text-green-700">
                Expense<span class="text-green-500">Mate</span>
            </h1>
            <p class="text-gray-500">Smart Spending. Simple Tracking.</p>
        </div>

        {{-- Form Card --}}
        <div class="w-full max-w-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                Welcome Back! 👋
            </h2>
            <p class="text-gray-500 mb-8">
                Login to your account
            </p>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="bg-green-100 text-green-700
                            px-4 py-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-gray-600
                                  font-medium mb-1">
                        Email Address
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required autofocus
                           class="w-full border-2 rounded-lg px-4 py-3
                                  focus:outline-none focus:border-green-500
                                  @error('email') border-red-400 @enderror"
                           placeholder="your@email.com" />
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label class="block text-gray-600
                                  font-medium mb-1">
                        Password
                    </label>
                    <input type="password"
                           name="password"
                           required
                           class="w-full border-2 rounded-lg px-4 py-3
                                  focus:outline-none focus:border-green-500
                                  @error('password') border-red-400 @enderror"
                           placeholder="Enter your password" />
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 text-gray-600">
                        <input type="checkbox"
                               name="remember"
                               class="rounded border-gray-300
                                      text-green-600" />
                        <span class="text-sm">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-green-600
                                  hover:underline">
                            Forgot password?
                        </a>
                    @endif
                </div>

                {{-- Login Button --}}
                <button type="submit"
                        class="w-full bg-green-700 text-white py-3
                               rounded-lg font-semibold hover:bg-green-800
                               transition text-lg">
                    Login Now
                </button>

                {{-- Register Link --}}
                <p class="text-center text-gray-500 mt-6">
                    Don't have an account?
                    <a href="{{ route('register') }}"
                       class="text-green-600 font-semibold hover:underline">
                        Sign up here
                    </a>
                </p>

            </form>
        </div>
    </div>

</div>

</body>
</html>
