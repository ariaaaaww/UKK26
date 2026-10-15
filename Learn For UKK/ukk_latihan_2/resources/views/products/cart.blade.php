@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <button class=" bg-blue-800 px-5 py-3 rounded-lg text-white">
            <a href="{{ route('products.index') }}">Halaman Utama</a>
        </button>
    </div>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <form method="POST" action="{{ route('products.cart.clear') }}"  class="flex justify-end mx-2">
            <button class=" bg-blue-800 px-5 py-3 rounded-lg text-white">
                Kosongkan
            </button>
        </form>
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">price</th>
                        <th class="p-4 text-center">Qty</th>
                        <th class="p-4 text-center">Subtotal</th>
                        <th class="p-4 text-center">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $id => $item)
                        <tr class="border-t hover:bg-gray-50">

                            {{-- name & code --}}
                            <td class="p-4">
                                <p class="font-medium text-gray-800">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-400">#{{ $item['code'] ?? '-' }}</p>
                            </td>

                            {{-- price --}}
                            <td class="p-4 text-center text-gray-700">
                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </td>

                            {{-- Qty dengan tombol + / - --}}
                            <td class="p-4 text-center">
                                <div class="inline-flex items-center gap-2">

                                    {{-- Tombol Kurang (-) --}}
                                    <form action="{{ route('products.cart.update', $item['id'] ?? $id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit"
                                            class="w-7 h-7 rounded-full bg-gray-200 hover:bg-gray-300 font-bold text-gray-700">
                                            −
                                        </button>
                                    </form>

                                    <span
                                        class="w-6 text-center font-semibold">{{ $item['qty'] ?? ($item['quantity'] ?? 1) }}</span>

                                    {{-- Tombol Tambah (+) --}}
                                    <form action="{{ route('products.cart.update', $item['id'] ?? $id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="increase">
                                        <button type="submit"
                                            class="w-7 h-7 rounded-full bg-blue-500 hover:bg-blue-600 font-bold text-white">
                                            +
                                        </button>
                                    </form>

                                </div>
                            </td>

                            {{-- Subtotal --}}
                            <td class="p-4 text-center font-medium text-gray-800">
                                Rp
                                {{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? ($item['quantity'] ?? 1)), 0, ',', '.') }}
                            </td>

                            {{-- Hapus --}}
                            <td class="p-4 text-center">
                                <form action="{{ route('products.cart.remove', $item['id'] ?? $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400">
                                Keranjang masih kosong.<br>
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if (count($products) > 0)
                    <tfoot class="bg-gray-50 border-t">
                        <tr>
                            <td colspan="3" class="p-4 text-right font-semibold text-gray-700">Total Belanja</td>
                            <td class="p-4 text-center font-bold text-blue-600 text-base">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
