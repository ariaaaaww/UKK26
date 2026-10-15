<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">🛒 Keranjang Belanja</h1>
        <a href="{{ route('products.index') }}" class="text-blue-500 hover:underline text-sm">
            <- Kembali ke Produk </a>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">

        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow overflow-hidden">

            {{-- Header tabel + tombol kosongkan --}}
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h2 class="font-semibold text-gray-700">Item dalam keranjang</h2>
                @if (count($cartItems) > 0)
                    <form action="{{ route('products.cart.clear') }}" method="POST"
                        onsubmit="return confirm('Yakin ingin mengosongkan keranjang?')">
                        @csrf
                        <button type="submit"
                            class="bg-red-500 text-white text-sm px-4 py-2 rounded-lg hover:bg-red-600">
                            Kosongkan
                        </button>
                    </form>
                @endif
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">Harga</th>
                        <th class="p-4 text-center">Qty</th>
                        <th class="p-4 text-center">Subtotal</th>
                        <th class="p-4 text-center">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cartItems as $item)
                        <tr class="border-t hover:bg-gray-50">

                            {{-- Nama & Kode --}}
                            <td class="p-4">
                                <p class="font-medium text-gray-800">{{ $item['nama'] }}</p>
                                <p class="text-xs text-gray-400">#{{ $item['kode'] }}</p>
                            </td>

                            {{-- Harga --}}
                            <td class="p-4 text-center text-gray-700">
                                Rp {{ number_format($item['harga'], 0, ',', '.') }}
                            </td>

                            {{-- Qty dengan tombol + / - --}}
                            <td class="p-4 text-center">
                                <div class="inline-flex items-center gap-2">

                                    {{-- Tombol Kurang (-) --}}
                                    <form action="{{ route('products.cart.update', $item['id']) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit"
                                            class="w-7 h-7 rounded-full bg-gray-200 hover:bg-gray-300 font-bold text-gray-700">
                                            −
                                        </button>
                                    </form>

                                    <span class="w-6 text-center font-semibold">{{ $item['qty'] }}</span>

                                    {{-- Tombol Tambah (+) --}}
                                    <form action="{{ route('products.cart.update', $item['id']) }}" method="POST">
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
                                Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}
                            </td>

                            {{-- Hapus --}}
                            <td class="p-4 text-center">
                                <form action="{{ route('products.cart.remove', $item['id']) }}" method="POST">
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
                                <a href="{{ route('products.index') }}"
                                    class="text-blue-500 hover:underline mt-2 inline-block">
                                    Mulai belanja →
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if (count($cartItems) > 0)
                    <tfoot class="bg-gray-50 border-t">
                        <tr>
                            <td colspan="3" class="p-4 text-right font-semibold text-gray-700">Total Belanja</td>
                            <td class="p-4 text-center font-bold text-blue-600 text-base">
                                Rp {{ number_format($totalBelanja, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

</body>

</html>
