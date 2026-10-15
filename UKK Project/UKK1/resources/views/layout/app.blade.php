<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', $title)</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class=" bg-gray-100">
    @include('layouts.partials.header')

    {{-- flex-1 flex  --}}
    <main class="flex flex-col min-h-screen mx-10 pt-5">
        @yield('content')
    </main>

    @include('layouts.partials.footer')
</body>

</html>
