@extends('layouts.app')

@section('title', $menu['name'])

@section('content')
    <a href="{{ route('menus.index') }}" class="text-amber-800">&larr; Kembali ke menu</a>

    <div class="mt-4 bg-white rounded-xl shadow p-6 grid md:grid-cols-2 gap-8">
        <img src="{{ $menu['image'] }}" alt="{{ $menu['name'] }}" class="w-full rounded-lg">

        <div>
            <span class="text-sm font-semibold text-amber-700">{{ $menu['category'] }}</span>
            <h1 class="text-3xl font-bold">{{ $menu['name'] }}</h1>
            <p class="text-2xl font-bold text-amber-800 my-3">
                Rp {{ number_format($menu['price'], 0, ',', '.') }}
            </p>
            <p class="text-stone-600 mb-4">{{ $menu['description'] }}</p>
            <p class="text-sm">Stok tersedia: <strong>{{ $menu['stock'] }}</strong></p>

            {{-- Kasus 7: form tambah ke keranjang --}}
            @if ($menu['stock'] > 0)
                <form action="{{ route('cart.store', $menu['id']) }}" method="POST"
                      class="mt-6 flex items-center gap-3">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="{{ $menu['stock'] }}"
                           class="w-24 border rounded-lg px-3 py-2">
                    <button type="submit" class="bg-amber-800 text-white px-6 py-2 rounded-lg">
                        Tambah ke Keranjang
                    </button>
                </form>
            @else
                <button disabled class="mt-6 bg-stone-300 text-stone-600 px-6 py-2 rounded-lg">
                    Stok Habis
                </button>
            @endif
        </div>
    </div>
@endsection
