@extends('layouts.app')

@section('title', 'Menu')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Menu Kopi Senja</h1>

    {{-- Kasus 2 & 4: pencarian dan urutan --}}
    <form action="{{ route('menus.index') }}" method="GET" class="flex flex-wrap gap-3 mb-4">
        <input type="hidden" name="category" value="{{ $category }}">

        <input type="text" name="search" value="{{ $search }}"
               placeholder="Cari menu..."
               class="flex-1 border rounded-lg px-4 py-2">

        <select name="sort" class="border rounded-lg px-4 py-2">
            <option value="">Urutkan</option>
            <option value="cheapest" @selected($sort === 'cheapest')>Harga termurah</option>
            <option value="most_expensive" @selected($sort === 'most_expensive')>Harga termahal</option>
        </select>

        <button type="submit" class="bg-amber-800 text-white px-5 py-2 rounded-lg">Cari</button>
    </form>

    {{-- Kasus 3: tombol kategori --}}
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('menus.index', ['search' => $search, 'sort' => $sort]) }}"
           class="px-4 py-1 rounded-full border {{ $category ? 'bg-white' : 'bg-amber-800 text-white' }}">
            Semua
        </a>

        @foreach ($categories as $categoryName)
            <a href="{{ route('menus.index', ['category' => $categoryName, 'search' => $search, 'sort' => $sort]) }}"
               class="px-4 py-1 rounded-full border {{ $category === $categoryName ? 'bg-amber-800 text-white' : 'bg-white' }}">
                {{ $categoryName }}
            </a>
        @endforeach
    </div>

    {{-- Kasus 1: grid menu --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($menus as $menu)
            <div class="bg-white rounded-xl shadow overflow-hidden {{ $menu['stock'] === 0 ? 'opacity-50' : '' }}">
                <img src="{{ $menu['image'] }}" alt="{{ $menu['name'] }}"
                     class="w-full h-40 object-cover">

                <div class="p-4">
                    <span class="text-xs font-semibold text-amber-700">{{ $menu['category'] }}</span>
                    <h2 class="text-lg font-bold">{{ $menu['name'] }}</h2>
                    <p class="font-bold text-amber-800">
                        Rp {{ number_format($menu['price'], 0, ',', '.') }}
                    </p>

                    {{-- Kasus 6: label stok --}}
                    @if ($menu['stock'] === 0)
                        <span class="inline-block mt-2 text-xs bg-red-100 text-red-700 px-2 py-1 rounded">
                            Habis
                        </span>
                    @elseif ($menu['stock'] <= 5)
                        <span class="inline-block mt-2 text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded">
                            Sisa {{ $menu['stock'] }}
                        </span>
                    @endif

                    {{-- Kasus 5: link detail --}}
                    <a href="{{ route('menus.show', $menu['id']) }}"
                       class="block mt-3 text-center bg-amber-800 text-white py-2 rounded-lg">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-stone-500 py-12">Menu tidak ditemukan.</p>
        @endforelse
    </div>
@endsection
