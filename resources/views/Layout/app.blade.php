<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Home') · QuoteIt</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-tuwaiq-night text-tuwaiq-text antialiased">
<canvas id="light-scene" class="pointer-events-none fixed top-0 left-0 -z-20 w-full" aria-hidden="true"></canvas>
<canvas id="light-rain" class="pointer-events-none fixed top-0 left-0 -z-10 w-full" aria-hidden="true"></canvas>

<x-navbar :page="$page ?? ''" />


    <main>
        @yield('content')
    </main>

</body>

</html>
