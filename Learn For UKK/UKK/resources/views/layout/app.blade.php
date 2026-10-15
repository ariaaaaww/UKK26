<!DOCTYPE html>
<html lang="id" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $title)</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column h-100 bg-light">

    {{-- Partial Header --}}
    @include('layouts.header')

    {{-- Main Content Container --}}
    <main class="flex-shrink-0 my-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    {{-- Partial Footer --}}
    @include('layouts.footer')
</body>
</html>