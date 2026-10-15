@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <button class=" bg-blue-800 px-5 py-3 rounded-lg text-white">
            <a href="{{ route('products.cart') }}">Lihat Keranjang</a>
        </button>
    </div>
    <div class="max-w-6xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
            {{--  lg:grid-cols-5  --}}
            @forelse ($products as $product)
                <div class="bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden flex flex-col">

                    {{-- picture --}}
                    <img src="{{ asset('storage/' . $product['picture']) }}" alt="{{ $product['name'] }}"
                        class="w-full h-44 object-cover">

                    {{-- Info Produk --}}
                    <div class="p-4 flex flex-col flex-1">
                        <p class="text-xs text-gray-400 mb-1">#{{ $product['code'] }}</p>
                        <h2 class="font-semibold text-gray-800 mb-1">{{ $product['name'] }}</h2>
                        <p class="text-blue-600 font-bold mb-4">
                            Rp {{ number_format($product['price'], 0, ',', '.') }}
                        </p>

                        {{-- Tombol Add to Cart --}}
                        <form action="{{ route('products.cart.add', ['productId' => $product['id']]) }}" method="POST"
                            class="mt-auto">
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

    {{-- Coba --}}
    {{-- <div class="max-w-6xl w-full mx-auto p-8">
        <!-- Grid System 3 Kolom -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            @forelse ($products as $product)
                <!-- Card Item -->
                <div class="bg-white rounded-2xl shadow-md overflow-hidden p-3 flex flex-col justify-between">
                    <div>
                        <!-- Gambar Produk -->
                        <div class="w-full h-64 rounded-xl overflow-hidden mb-4">
                            <img src="{{ asset('storage/' . $product['picture']) }}" alt="{{ $product['name'] }}"
                                class="w-full h-full object-cover">
                        </div>
                        <!-- Detail Produk -->
                        <div class="px-2">
                            <span
                                class="text-gray-400 text-xs font-semibold">#{{ $product['code'] ?? $product['id'] }}</span>
                            <h3 class="text-gray-800 font-bold text-lg mt-1">{{ $product['name'] }}</h3>
                            <p class="text-blue-600 font-bold text-xl mt-1">
                                Rp {{ number_format($product['price'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <!-- Tombol Tambah ke Keranjang -->
                    <div class="mt-4 px-2 pb-1">
                        <form action="{{ route('products.cart.add', $product['id']) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-xl transition duration-200">
                                + Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <!-- Tampilan Jika Data Kosong -->
                <div class="col-span-1 md:col-span-3 bg-white p-8 rounded-2xl text-center text-gray-500 shadow-sm">
                    <p class="font-medium">Belum ada data product yang tersedia.</p>
                </div>
            @endforelse

        </div>
    </div> --}}
@endsection
