<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

/**
 * Service class untuk mengelola data menu kafe Kopi Senja
 * Mengambil dan memanipulasi data yang bersumber dari file JSON.
 */
class MenuService
{
    /**
     * Membaca seluruh data menu dari berkas storage/app/data/menus.json
     * Mendekode string JSON menjadi array asosiatif dan mengembalikannya sebagai Laravel Collection.
     */
    public static function all(): Collection
    {
        // Menentukan path absolut menuju file data menus.json di direktori storage
        $path = storage_path('app/data/menus.json');

        // Memeriksa keberadaan file JSON sebelum dibaca untuk mencegah error
        if (! File::exists($path)) {
            return collect();
        }

        // Membaca isi berkas JSON mentah
        $json = File::get($path);

        // Mendekode JSON menjadi array PHP (parameter true menghasilkan array asosiatif)
        $menus = json_decode($json, true);

        // Mengembalikan data menu yang telah dibungkus ke dalam Laravel Collection
        return collect(is_array($menus) ? $menus : []);
    }

    /**
     * Mencari dan mengembalikan satu data menu berdasarkan ID
     * Menggunakan method firstWhere dari Laravel Collection.
     * Mengembalikan null jika menu dengan ID tersebut tidak ditemukan.
     */
    public static function find(int $id): ?array
    {
        // Mencari elemen pertama dalam collection yang memiliki nilai kolom 'id' sama dengan $id
        return self::all()->firstWhere('id', $id);
    }
}
