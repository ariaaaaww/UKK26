@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Keranjang</h1>

    @if (empty($cartItems))
        {{-- Tampilan saat keranjang belanja kosong --}}
        <div class="bg-white rounded-xl shadow p-10 text-center">
            <p class="text-stone-500 mb-4">Keranjang masih kosong.</p>
            <a href="{{ route('menus.index') }}" class="bg-amber-800 text-white px-5 py-2 rounded-lg">
                Lihat Menu
            </a>
        </div>
    @else
        <div class="grid lg:grid-cols-3 gap-6">

            {{-- KIRI: daftar item keranjang belanja --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow overflow-x-auto h-fit">
                <table class="w-full text-left">
                    <thead class="bg-stone-50">
                        <tr>
                            <th class="p-3">Menu</th>
                            <th class="p-3">Harga</th>
                            <th class="p-3">Jumlah</th>
                            <th class="p-3">Subtotal</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr class="border-t">
                                <td class="p-3 font-semibold">{{ $item['name'] }}</td>
                                <td class="p-3">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>

                                {{-- Kasus 9: tombol - dan + jumlah item --}}
                                <td class="p-3">
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('cart.update', $item['id']) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="w-8 h-8 rounded-full bg-stone-200">-</button>
                                        </form>

                                        <span class="w-6 text-center font-semibold">{{ $item['quantity'] }}</span>

                                        <form action="{{ route('cart.update', $item['id']) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit"
                                                class="w-8 h-8 rounded-full bg-amber-800 text-white">+</button>
                                        </form>
                                    </div>
                                </td>

                                {{-- Subtotal per item = harga x jumlah --}}
                                <td class="p-3">
                                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                </td>

                                {{-- Kasus 9: tombol hapus satu item --}}
                                <td class="p-3">
                                    <form action="{{ route('cart.destroy', $item['id']) }}" method="POST"
                                        onsubmit="return confirm('Hapus item ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Kasus 9: tombol kosongkan seluruh keranjang --}}
                <form action="{{ route('cart.clear') }}" method="POST" class="p-3 border-t"
                    onsubmit="return confirm('Kosongkan semua isi keranjang?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 text-sm">Kosongkan Keranjang</button>
                </form>
            </div>

            {{-- KANAN: ringkasan, kode promo, dan checkout --}}
            <div class="bg-white rounded-xl shadow p-5 h-fit">
                <h2 class="text-lg font-bold mb-4">Ringkasan</h2>

                {{-- Kasus 11: form kode promo --}}
                @if ($summary['promo_code'])
                    <div class="flex justify-between items-center bg-green-50 rounded-lg px-3 py-2 mb-4">
                        <span class="text-sm">Promo <strong>{{ $summary['promo_code'] }}</strong> dipakai</span>
                        <form action="{{ route('promo.remove') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600">Batal</button>
                        </form>
                    </div>
                @else
                    <form action="{{ route('promo.apply') }}" method="POST" class="flex gap-2 mb-4">
                        @csrf
                        <input type="text" name="promo_code" placeholder="Kode promo"
                            class="flex-1 border rounded-lg px-3 py-2 uppercase">
                        <button type="submit" class="bg-stone-700 text-white px-4 rounded-lg">Pakai</button>
                    </form>
                @endif

                {{-- Kasus 10: rincian biaya (subtotal, diskon, promo, service, PPN, total) --}}
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($summary['subtotal'], 0, ',', '.') }}</span>
                    </div>

                    @if ($summary['discount'] > 0)
                        <div class="flex justify-between text-green-700">
                            <span>Diskon 5%</span>
                            <span>- Rp {{ number_format($summary['discount'], 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if ($summary['promo_discount'] > 0)
                        <div class="flex justify-between text-green-700">
                            <span>Promo {{ $summary['promo_code'] }}</span>
                            <span>- Rp {{ number_format($summary['promo_discount'], 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <span>Service charge 5%</span>
                        <span>Rp {{ number_format($summary['service_charge'], 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>PPN 11%</span>
                        <span>Rp {{ number_format($summary['tax'], 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between border-t pt-3 text-lg font-bold">
                        <span>Total</span>
                        <span>Rp {{ number_format($summary['grand_total'], 0, ',', '.') }}</span>
                    </div>
                </div>

                @if ($summary['remaining_for_discount'] > 0)
                    <p class="mt-3 text-xs text-stone-500">
                        Belanja Rp {{ number_format($summary['remaining_for_discount'], 0, ',', '.') }}
                        lagi untuk mendapat diskon 5%.
                    </p>
                @endif

                {{-- Kasus 12: formulir checkout pesanan --}}
                <form action="{{ route('orders.store') }}" method="POST" class="mt-6 space-y-3 border-t pt-4">
                    @csrf
                    <h3 class="font-bold">Data Pemesan</h3>

                    <div>
                        <label class="block text-sm mb-1">Nama pemesan</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                            class="w-full border rounded-lg px-3 py-2">
                        @error('customer_name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Nomor meja</label>
                        <input type="number" name="table_number" value="{{ old('table_number') }}" min="1"
                            max="20" class="w-full border rounded-lg px-3 py-2">
                        @error('table_number')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Uang dibayar</label>
                        <input type="number" name="cash" value="{{ old('cash') }}"
                            class="w-full border rounded-lg px-3 py-2">
                        @error('cash')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-amber-800 text-white py-2 rounded-lg font-semibold">
                        Bayar Sekarang
                    </button>
                </form>
            </div>

        </div>
    @endif
@endsection
