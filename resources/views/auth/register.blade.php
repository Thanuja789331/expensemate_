<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseMate — Register</title>
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

        {{-- Why Join --}}
        <div class="space-y-4 w-full max-w-sm">
            <div class="flex items-center gap-3 bg-green-600
                        rounded-lg p-3">
                <span class="text-2xl">✅</span>
                <div>
                    <p class="font-bold">Free to Use</p>
                    <p class="text-green-200 text-sm">
                        No hidden charges
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 bg-green-600
                        rounded-lg p-3">
                <span class="text-2xl">📱</span>
                <div>
                    <p class="font-bold">Works Everywhere</p>
                    <p class="text-green-200 text-sm">
                        Mobile and desktop friendly
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 bg-green-600
                        rounded-lg p-3">
                <span class="text-2xl">💡</span>
                <div>
                    <p class="font-bold">Smart Insights</p>
                    <p class="text-green-200 text-sm">
                        Understand your spending
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Right Side — Register Form --}}
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
                Create Account 🎉
            </h2>
            <p class="text-gray-500 mb-8">
                Start tracking your expenses today
            </p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Full Name --}}
                <div class="mb-4">
                    <label class="block text-gray-600
                                  font-medium mb-1">
                        Full Name
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required autofocus
                           class="w-full border-2 rounded-lg px-4 py-3
                                  focus:outline-none focus:border-green-500
                                  @error('name') border-red-400 @enderror"
                           placeholder="Enter your full name" />
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-gray-600
                                  font-medium mb-1">
                        Email Address
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
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
                           placeholder="Create a strong password" />
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label class="block text-gray-600
                                  font-medium mb-1">
                        Confirm Password
                    </label>
                    <input type="password"
                           name="password_confirmation"
                           required
                           class="w-full border-2 rounded-lg px-4 py-3
                                  focus:outline-none focus:border-green-500"
                           placeholder="Repeat your password" />
                </div>

                {{-- Register Button --}}
                <button type="submit"
                        class="w-full bg-green-700 text-white py-3
                               rounded-lg font-semibold hover:bg-green-800
                               transition text-lg">
                    Create Account
                </button>

                {{-- Login Link --}}
                <p class="text-center text-gray-500 mt-6">
                    Already have an account?
                    <a href="{{ route('login') }}"
                       class="text-green-600 font-semibold hover:underline">
                        Login here
                    </a>
                </p>

            </form>
        </div>
    </div>

</div>

</body>
</html>
