<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Home') · QuoteIt</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="min-h-screen bg-tuwaiq-night bg-fixed bg-center bg-no-repeat text-tuwaiq-text antialiased"
    style="
        background-image: url('{{ asset('images/background-lights.jpg') }}');
        background-size: cover;
    "
>
<canvas id="light-rain" class="pointer-events-none fixed inset-0 -z-10 h-full w-full" aria-hidden="true"></canvas>

<x-navbar :page="$page ?? ''" />


    <main>
        @yield('content')
    </main>

</body>

</html>
