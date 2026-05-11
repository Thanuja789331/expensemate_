<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseMate — Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<div class="min-h-screen flex flex-col
            justify-center items-center p-8">

    {{-- Illustration --}}
    <img src="{{ asset('images/404.svg') }}"
         alt="Page Not Found"
         class="w-72 h-72 mb-8" />

    {{-- Error Code --}}
    <h1 class="text-8xl font-bold text-green-700 mb-4">
        404
    </h1>

    {{-- Message --}}
    <h2 class="text-2xl font-bold text-gray-800 mb-2">
        Oops! Page Not Found
    </h2>
    <p class="text-gray-500 text-center max-w-md mb-8">
        The page you are looking for doesn't exist
        or has been moved.
    </p>

    {{-- Actions --}}
    <div class="flex gap-4">
        <a href="{{ route('dashboard') }}"
           class="bg-green-700 text-white px-8 py-3
                  rounded-lg hover:bg-green-800
                  font-medium transition">
            🏠 Go to Dashboard
        </a>
        <a href="{{ url()->previous() }}"
           class="bg-gray-200 text-gray-700 px-8 py-3
                  rounded-lg hover:bg-gray-300
                  font-medium transition">
            ← Go Back
        </a>
    </div>

    {{-- Branding --}}
    <p class="text-gray-400 mt-12 text-sm">
        ExpenseMate — Smart Spending. Simple Tracking.
    </p>

</div>

</body>
</html>
