@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Riwayat Pesanan</h1>

    {{-- Kasus 14: jika riwayat pesanan kosong --}}
    @if (empty($orders))
        <p class="bg-white rounded-xl shadow p-10 text-center text-stone-500">
            Belum ada pesanan.
        </p>
    @else
        {{-- Ringkasan statistik jumlah pesanan dan total pendapatan --}}
        <div class="grid md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-sm text-stone-500">Jumlah pesanan</p>
                <p class="text-3xl font-bold">{{ count($orders) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-sm text-stone-500">Total pendapatan</p>
                <p class="text-3xl font-bold text-amber-800">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Tabel daftar riwayat pesanan --}}
        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-stone-50">
                    <tr>
                        <th class="p-3">Kode</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Pemesan</th>
                        <th class="p-3">Meja</th>
                        <th class="p-3">Total</th>
                        <th class="p-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-t">
                            <td class="p-3 font-mono text-sm">{{ $order['code'] }}</td>
                            <td class="p-3">{{ $order['date'] }}</td>
                            <td class="p-3">{{ $order['customer_name'] }}</td>
                            <td class="p-3">{{ $order['table_number'] }}</td>
                            <td class="p-3 font-semibold">
                                Rp {{ number_format($order['summary']['grand_total'], 0, ',', '.') }}
                            </td>
                            <td class="p-3">
                                <a href="{{ route('orders.show', $order['code']) }}" class="text-amber-800">
                                    Lihat struk
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
