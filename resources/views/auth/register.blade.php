<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseMate — Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<div class="min-h-screen flex">

    {{-- Left Side — Branding --}}
    <div class="hidden md:flex md:w-1/2 bg-green-700
                flex-col justify-center items-center p-12 text-white">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold mb-2">
                Expense<span class="text-green-300">Mate</span>
            </h1>
            <p class="text-green-200 text-lg">
                Smart Spending. Simple Tracking.
            </p>
        </div>

        {{-- Illustration --}}
        <div class="w-72 h-72 mb-8">
            <img src="{{ asset('images/register.svg') }}"
                 alt="Register Illustration"
                 class="w-full h-full object-contain
                        drop-shadow-lg" />
        </div>

        {{-- Why Join --}}
        <div class="space-y-3 w-full max-w-sm">
            <div class="flex items-center gap-3 bg-green-600
                        bg-opacity-50 rounded-lg p-3">
                <span class="text-2xl">✅</span>
                <div>
                    <p class="font-bold text-sm">Free to Use</p>
                    <p class="text-green-200 text-xs">
                        No hidden charges ever
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 bg-green-600
                        bg-opacity-50 rounded-lg p-3">
                <span class="text-2xl">📱</span>
                <div>
                    <p class="font-bold text-sm">
                        Works Everywhere
                    </p>
                    <p class="text-green-200 text-xs">
                        Mobile and desktop friendly
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 bg-green-600
                        bg-opacity-50 rounded-lg p-3">
                <span class="text-2xl">🛡️</span>
                <div>
                    <p class="font-bold text-sm">100% Secure</p>
                    <p class="text-green-200 text-xs">
                        Your data is always protected
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Side — Register Form --}}
    <div class="w-full md:w-1/2 flex flex-col
                justify-center items-center p-8 bg-white">

        {{-- Mobile Logo --}}
        <div class="md:hidden text-center mb-8">
            <img src="{{ asset('images/register.svg') }}"
                 alt="ExpenseMate"
                 class="w-32 h-32 mx-auto mb-4" />
            <h1 class="text-3xl font-bold text-green-700">
                Expense<span class="text-green-500">Mate</span>
            </h1>
            <p class="text-gray-500">
                Smart Spending. Simple Tracking.
            </p>
        </div>

        {{-- Form --}}
        <div class="w-full max-w-md">

            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    Create Account 🎉
                </h2>
                <p class="text-gray-500">
                    Start tracking your expenses today — it's free!
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Full Name --}}
                <div class="mb-4">
                    <label class="block text-gray-600
                                  font-medium mb-1">
                        Full Name
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-3.5
                                     text-gray-400 text-lg">
                            👤
                        </span>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               required autofocus
                               class="w-full border-2 rounded-lg
                                      pl-10 pr-4 py-3
                                      focus:outline-none
                                      focus:border-green-500
                                      @error('name')
                                          border-red-400
                                      @enderror"
                               placeholder="Enter your full name" />
                    </div>
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
                    <div class="relative">
                        <span class="absolute left-3 top-3.5
                                     text-gray-400 text-lg">
                            📧
                        </span>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               class="w-full border-2 rounded-lg
                                      pl-10 pr-4 py-3
                                      focus:outline-none
                                      focus:border-green-500
                                      @error('email')
                                          border-red-400
                                      @enderror"
                               placeholder="your@email.com" />
                    </div>
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
                    <div class="relative">
                        <span class="absolute left-3 top-3.5
                                     text-gray-400 text-lg">
                            🔒
                        </span>
                        <input type="password"
                               name="password"
                               required
                               class="w-full border-2 rounded-lg
                                      pl-10 pr-4 py-3
                                      focus:outline-none
                                      focus:border-green-500
                                      @error('password')
                                          border-red-400
                                      @enderror"
                               placeholder="Create a strong password" />
                    </div>
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
                    <div class="relative">
                        <span class="absolute left-3 top-3.5
                                     text-gray-400 text-lg">
                            🔑
                        </span>
                        <input type="password"
                               name="password_confirmation"
                               required
                               class="w-full border-2 rounded-lg
                                      pl-10 pr-4 py-3
                                      focus:outline-none
                                      focus:border-green-500"
                               placeholder="Repeat your password" />
                    </div>
                </div>

                {{-- Register Button --}}
                <button type="submit"
                        class="w-full bg-green-700 text-white
                               py-3 rounded-lg font-semibold
                               hover:bg-green-800 transition
                               text-lg shadow-lg
                               shadow-green-200 mb-4">
                    🚀 Create Free Account
                </button>

                {{-- Divider --}}
                <div class="flex items-center gap-3 my-4">
                    <hr class="flex-1 border-gray-200">
                    <span class="text-gray-400 text-sm">
                        or continue with
                    </span>
                    <hr class="flex-1 border-gray-200">
                </div>

                {{-- Google Sign Up --}}
                <a href="{{ route('auth.google') }}"
                   class="w-full flex items-center justify-center
                          gap-3 bg-white border-2 border-gray-200
                          text-gray-700 py-3 rounded-lg font-semibold
                          hover:bg-gray-50 hover:border-gray-300
                          transition mb-6">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4"
                              d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26
                                 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92
                                 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853"
                              d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23
                                 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99
                                 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05"
                              d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43
                                 .35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43
                                 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335"
                              d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45
                                 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66
                                 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Sign up with Google
                </a>

                {{-- Login Link --}}
                <div class="text-center bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-600">
                        Already have an account?
                    </p>
                    <a href="{{ route('login') }}"
                       class="text-green-600 font-bold
                              hover:underline text-lg">
                        Login here →
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

</body>
</html>
