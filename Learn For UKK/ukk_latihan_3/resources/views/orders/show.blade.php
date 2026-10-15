@extends('layouts.app')

@section('title', 'Struk ' . $order['code'])

@section('content')
    {{-- Tampilan Struk Pembayaran (Kasus 13) --}}
    <div class="max-w-sm mx-auto bg-white shadow p-6 font-mono text-sm">
        <div class="text-center mb-4">
            <h1 class="text-lg font-bold">KOPI SENJA</h1>
            <p>Jl. Merdeka No. 10</p>
        </div>

        {{-- Nomor transaksi, tanggal, pemesan, dan meja --}}
        <div class="border-y border-dashed py-2 mb-3">
            <p>No      : {{ $order['code'] }}</p>
            <p>Tanggal : {{ $order['date'] }}</p>
            <p>Nama    : {{ $order['customer_name'] }}</p>
            <p>Meja    : {{ $order['table_number'] }}</p>
        </div>

        {{-- Daftar item yang dipesan --}}
        @foreach ($order['items'] as $item)
            <p>{{ $item['name'] }}</p>
            <div class="flex justify-between mb-1">
                <span>{{ $item['quantity'] }} x {{ number_format($item['price'], 0, ',', '.') }}</span>
                <span>{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
            </div>
        @endforeach

        {{-- Rincian pembayaran: subtotal, diskon, promo, service, PPN, grand total, tunai, kembali --}}
        <div class="border-t border-dashed mt-3 pt-3 space-y-1">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>{{ number_format($order['summary']['subtotal'], 0, ',', '.') }}</span>
            </div>

            @if ($order['summary']['discount'] > 0)
                <div class="flex justify-between">
                    <span>Diskon 5%</span>
                    <span>-{{ number_format($order['summary']['discount'], 0, ',', '.') }}</span>
                </div>
            @endif

            @if ($order['summary']['promo_discount'] > 0)
                <div class="flex justify-between">
                    <span>Promo {{ $order['summary']['promo_code'] }}</span>
                    <span>-{{ number_format($order['summary']['promo_discount'], 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="flex justify-between">
                <span>Service 5%</span>
                <span>{{ number_format($order['summary']['service_charge'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>PPN 11%</span>
                <span>{{ number_format($order['summary']['tax'], 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between font-bold text-base border-t border-dashed pt-2">
                <span>TOTAL</span>
                <span>{{ number_format($order['summary']['grand_total'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Tunai</span>
                <span>{{ number_format($order['cash'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Kembali</span>
                <span>{{ number_format($order['change'], 0, ',', '.') }}</span>
            </div>
        </div>

        <p class="text-center mt-6">Terima kasih atas kunjungan Anda!</p>
    </div>

    {{-- Tombol aksi cetak struk dan pesan lagi --}}
    <div class="text-center mt-6 space-x-2 print:hidden">
        <button onclick="window.print()" class="bg-stone-700 text-white px-5 py-2 rounded-lg">
            Cetak Struk
        </button>
        <a href="{{ route('menus.index') }}" class="bg-amber-800 text-white px-5 py-2 rounded-lg">
            Pesan Lagi
        </a>
    </div>
@endsection
