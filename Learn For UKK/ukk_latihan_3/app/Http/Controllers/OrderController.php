<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk mengelola checkout, struk, dan riwayat pesanan (Kasus 12, 13, & 14)
 */
class OrderController extends Controller
{
    /**
     * Menampilkan riwayat pesanan dan total pendapatan (Kasus 14)
     */
    public function index(): View
    {
        // array_reverse = pesanan terbaru tampil paling atas
        $orders = array_reverse(session('orders', []));

        // Jumlahkan grand_total dari semua pesanan
        $totalRevenue = collect($orders)->sum(fn ($order) => $order['summary']['grand_total']);

        return view('orders.index', compact('orders', 'totalRevenue'));
    }

    /**
     * Memproses form checkout belanja, validasi pembayaran, dan menyimpan struk (Kasus 12)
     */
    public function store(Request $request): RedirectResponse
    {
        $cartItems = CartService::items();

        // 1. Keranjang tidak boleh kosong
        if (empty($cartItems)) {
            return redirect()->route('menus.index')->with('error', 'Keranjang masih kosong.');
        }

        $summary = CartService::summary();

        // 2. Validasi form dengan pesan kustom bahasa Indonesia
        $request->validate([
            'customer_name' => 'required|string|max:50',
            'table_number' => 'required|integer|between:1,20',
            'cash' => 'required|integer|min:'.$summary['grand_total'],
        ], [
            'customer_name.required' => 'Nama pemesan wajib diisi.',
            'table_number.required' => 'Nomor meja wajib diisi.',
            'table_number.between' => 'Nomor meja harus antara 1 sampai 20.',
            'cash.required' => 'Uang yang dibayar wajib diisi.',
            'cash.min' => 'Uang yang dibayar kurang dari total belanja.',
        ]);

        // 3. Susun data pesanan
        $order = [
            'code' => 'ORD-'.now()->format('YmdHis'),
            'date' => now()->format('d-m-Y H:i'),
            'customer_name' => $request->customer_name,
            'table_number' => $request->integer('table_number'),
            'items' => $cartItems,
            'summary' => $summary,
            'cash' => $request->integer('cash'),
            'change' => $request->integer('cash') - $summary['grand_total'],
        ];

        // 4. Simpan ke riwayat, lalu kosongkan keranjang dan promo
        session()->push('orders', $order);
        session()->forget(['cart', 'promo_code']);

        // 5. Arahkan ke halaman struk (Kasus 13)
        return redirect()->route('orders.show', $order['code']);
    }

    /**
     * Menampilkan struk pembayaran pesanan berdasarkan kode (Kasus 13)
     */
    public function show(string $code): View
    {
        // Cari pesanan di riwayat berdasarkan kode
        $order = collect(session('orders', []))->firstWhere('code', $code);

        if (! $order) {
            abort(404);
        }

        return view('orders.show', compact('order'));
    }
}
