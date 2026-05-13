<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
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

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="min-h-screen bg-gray-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="font-sans text-gray-900 dark:text-slate-100 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
