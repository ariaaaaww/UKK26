<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\MenuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk mengelola keranjang belanja dan kode promo (Kasus 7, 8, 9, & 11)
 */
class CartController extends Controller
{
    /**
     * Menampilkan isi keranjang belanja dan ringkasan perhitungan (Kasus 8 & 10)
     */
    public function index(): View
    {
        $cartItems = CartService::items();
        $summary = CartService::summary();

        return view('cart.index', compact('cartItems', 'summary'));
    }

    /**
     * Menambahkan menu ke dalam keranjang session beserta validasi stok (Kasus 7)
     */
    public function store(Request $request, int $id): RedirectResponse
    {
        // 1. Validasi input jumlah
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // 2. Cari menu yang akan ditambahkan
        $menu = MenuService::find($id);

        if (! $menu) {
            abort(404);
        }

        // 3. Ambil keranjang lama dari session
        $cart = session('cart', []);

        // 4. Hitung jumlah baru = jumlah lama + jumlah yang ditambahkan
        $currentQuantity = $cart[$id]['quantity'] ?? 0;
        $newQuantity = $currentQuantity + $request->integer('quantity');

        // 5. Cek stok
        if ($newQuantity > $menu['stock']) {
            return back()->with('error', 'Stok tidak cukup. Sisa stok: '.$menu['stock']);
        }

        // 6. Simpan menu ke keranjang (id menu dipakai sebagai key)
        $cart[$id] = [
            'id' => $menu['id'],
            'name' => $menu['name'],
            'price' => $menu['price'],
            'image' => $menu['image'],
            'stock' => $menu['stock'],
            'quantity' => $newQuantity,
        ];

        // 7. Simpan kembali ke session
        session()->put('cart', $cart);

        return back()->with('success', $menu['name'].' berhasil ditambahkan ke keranjang.');
    }

    /**
     * Mengubah jumlah item (+ / -) di keranjang belanja (Kasus 9)
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $cart = session('cart', []);

        // Jika item tidak ada di keranjang, kembali saja
        if (! isset($cart[$id])) {
            return back();
        }

        if ($request->action === 'increase') {
            // Tidak boleh melebihi stok
            if ($cart[$id]['quantity'] >= $cart[$id]['stock']) {
                return back()->with('error', 'Jumlah sudah mencapai batas stok.');
            }

            $cart[$id]['quantity']++;
        }

        if ($request->action === 'decrease') {
            $cart[$id]['quantity']--;

            // Jika jumlah menjadi 0, hapus item
            if ($cart[$id]['quantity'] < 1) {
                unset($cart[$id]);
            }
        }

        session()->put('cart', $cart);

        return back();
    }

    /**
     * Menghapus satu item dari keranjang (Kasus 9)
     */
    public function destroy(int $id): RedirectResponse
    {
        $cart = session('cart', []);

        unset($cart[$id]);

        session()->put('cart', $cart);

        return back()->with('success', 'Item berhasil dihapus.');
    }

    /**
     * Mengosongkan seluruh isi keranjang dan promo dari session (Kasus 9 & 11)
     */
    public function clear(): RedirectResponse
    {
        session()->forget(['cart', 'promo_code']);

        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }

    /**
     * Memvalidasi dan menerapkan kode promo (Kasus 11)
     */
    public function applyPromo(Request $request): RedirectResponse
    {
        $request->validate([
            'promo_code' => 'required|string',
        ]);

        // Ubah ke huruf besar agar "hemat10" tetap dianggap benar
        $promoCode = strtoupper($request->promo_code);

        if (! array_key_exists($promoCode, CartService::PROMO_CODES)) {
            return back()->with('error', 'Kode promo tidak valid.');
        }

        session()->put('promo_code', $promoCode);

        return back()->with('success', 'Kode promo '.$promoCode.' berhasil dipakai.');
    }

    /**
     * Membatalkan kode promo aktif dari session (Kasus 11)
     */
    public function removePromo(): RedirectResponse
    {
        session()->forget('promo_code');

        return back()->with('success', 'Kode promo dibatalkan.');
    }
}
