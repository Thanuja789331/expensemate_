<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseMate — Confirm Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<div class="min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md bg-white rounded-xl shadow p-8">

        {{-- Logo --}}
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-green-700">
                Expense<span class="text-green-500">Mate</span>
            </h1>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-2">
            🔒 Confirm Password
        </h2>
        <p class="text-gray-500 text-sm mb-6">
            Please confirm your password before continuing.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-600 font-medium mb-1">
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

            <button type="submit"
                    class="w-full bg-green-700 text-white py-3
                           rounded-lg font-semibold hover:bg-green-800">
                ✅ Confirm Password
            </button>

        </form>
    </div>
</div>

</body>
</html>
