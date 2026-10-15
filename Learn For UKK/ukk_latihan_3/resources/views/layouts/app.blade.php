<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Kopi Senja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-stone-800">

    {{-- Navbar --}}
    <nav class="bg-amber-900 text-white">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('menus.index') }}" class="text-xl font-bold">Kopi Senja</a>
            <div class="flex gap-6">
                <a href="{{ route('menus.index') }}">Menu</a>
                <a href="{{ route('orders.index') }}">Riwayat</a>
                <a href="{{ route('cart.index') }}">
                    Keranjang ({{ collect(session('cart', []))->sum('quantity') }})
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-8">

        {{-- Pesan berhasil --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-100 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan gagal --}}
        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-100 text-red-800 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        {{-- Pesan error dari validasi form --}}
        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 text-red-800 px-4 py-3">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
