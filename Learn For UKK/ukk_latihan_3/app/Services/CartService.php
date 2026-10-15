<?php

namespace App\Services;

/**
 * Service class untuk mengelola keranjang belanja dan kalkulasi biaya pesanan (Kasus 10 & 11)
 * Berisi aturan konstanta diskon, service charge, PPN, dan kode promo.
 */
class CartService
{
    /** Minimal subtotal belanja untuk mendapatkan diskon */
    public const DISCOUNT_MINIMUM = 150000;

    /** Persentase diskon belanja (5%) */
    public const DISCOUNT_RATE = 0.05;

    /** Persentase biaya layanan / service charge (5%) */
    public const SERVICE_CHARGE_RATE = 0.05;

    /** Persentase pajak pertambahan nilai / PPN (11%) */
    public const TAX_RATE = 0.11;

    /** Daftar kode promo yang sah beserta nominal potongan tetap (Kasus 11) */
    public const PROMO_CODES = [
        'HEMAT10' => 10000,
        'NGOPI5' => 5000,
    ];

    /**
     * Mengambil semua item di keranjang belanja dari session (Kasus 8)
     * Mengembalikan array kosong jika belum ada item yang ditambahkan.
     */
    public static function items(): array
    {
        return session('cart', []);
    }

    /**
     * Menghitung ringkasan biaya transaksi belanja (Kasus 10 & 11):
     * 1. Subtotal (harga x jumlah seluruh item)
     * 2. Diskon 5% jika subtotal >= Rp150.000
     * 3. Potongan kode promo dari session('promo_code')
     * 4. Total setelah diskon & promo
     * 5. Service charge 5% dan PPN 11% dari total setelah diskon
     * 6. Grand total dan sisa belanja untuk mencapai batas diskon
     */
    public static function summary(): array
    {
        // 1. Subtotal = jumlah dari (harga x jumlah) semua item
        $subtotal = 0;
        foreach (self::items() as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // 2. Diskon 5% jika subtotal minimal Rp150.000
        $discount = 0;
        if ($subtotal >= self::DISCOUNT_MINIMUM) {
            $discount = (int) round($subtotal * self::DISCOUNT_RATE);
        }

        // 3. Potongan kode promo (jika ada di session 'promo_code')
        $promoCode = session('promo_code');
        $promoDiscount = $promoCode ? (self::PROMO_CODES[$promoCode] ?? 0) : 0;

        // 4. Total setelah semua potongan (tidak boleh minus dengan fungsi max)
        $totalAfterDiscount = max($subtotal - $discount - $promoDiscount, 0);

        // 5. Service charge (5%) dan PPN (11%) dihitung dari harga setelah diskon
        $serviceCharge = (int) round($totalAfterDiscount * self::SERVICE_CHARGE_RATE);
        $tax = (int) round($totalAfterDiscount * self::TAX_RATE);

        // 6. Total akhir yang wajib dibayar
        $grandTotal = $totalAfterDiscount + $serviceCharge + $tax;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'promo_code' => $promoCode,
            'promo_discount' => $promoDiscount,
            'total_after_discount' => $totalAfterDiscount,
            'service_charge' => $serviceCharge,
            'tax' => $tax,
            'grand_total' => $grandTotal,
            'remaining_for_discount' => max(self::DISCOUNT_MINIMUM - $subtotal, 0),
        ];
    }
}
