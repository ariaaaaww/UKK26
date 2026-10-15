<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">🛍 Toko Wallpaper</h1>
        <a href="{{ route('products.cart') }}"
            class="relative bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-2">
            🛒 Keranjang
            @if ($total > 0)
                <span
                    class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                    {{ $total }}
                </span>
            @endif
        </a>
    </nav>

    <div class="max-w-6xl mx-auto px-4 py-8">

        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @forelse ($products as $product)
                <div class="bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden flex flex-col">

                    {{-- Gambar --}}
                    <img src="{{ asset('storage/' . $product['gambar']) }}" alt="{{ $product['nama'] }}"
                        class="w-full h-44 object-cover">

                    {{-- Info Produk --}}
                    <div class="p-4 flex flex-col flex-1">
                        <p class="text-xs text-gray-400 mb-1">#{{ $product['kode'] }}</p>
                        <h2 class="font-semibold text-gray-800 mb-1">{{ $product['nama'] }}</h2>
                        <p class="text-blue-600 font-bold mb-4">
                            Rp {{ number_format($product['harga'], 0, ',', '.') }}
                        </p>

                        {{-- Tombol Add to Cart --}}
                        <form action="{{ route('products.cart.add', ['productId' => $product['id']]) }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product['id'] }}">
                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 text-sm font-medium">
                                + Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center text-gray-400 py-16">Belum ada produk.</div>
            @endforelse
        </div>
    </div>

</body>

</html>
