<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/sidebar.css', 'resources/js/app.js'])
</head>

<body>

    @include('layouts.sidebar')

    <main class="main-content">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow" style="padding: 1.5rem 2rem;">
                {{ $header }}
            </header>
        @endisset

        <div style="padding: 1.5rem 2rem;">
            {{ $slot }}
        </div>
    </main>

</body>

</html>
