<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    </head>
    <body class="antialiased">
        <div class="w-full relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 py-4">
            <div id="app">
                <router-view></router-view>
            </div>
        </div>

        <script src="{{ mix('js/app.js') }}"></script>
        @vite(['resources/js/app.js'])
    </body>
</html>
