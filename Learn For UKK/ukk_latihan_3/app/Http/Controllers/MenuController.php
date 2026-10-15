<?php

namespace App\Http\Controllers;

use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk mengelola tampilan katalog menu dan detail produk Kopi Senja
 */
class MenuController extends Controller
{
    /**
     * Menampilkan daftar katalog menu dengan fitur filter pencarian nama,
     * filter kategori menu, serta pengurutan berdasarkan harga termurah atau termahal.
     *
     * Algoritma:
     * 1. Ambil seluruh data menu dari MenuService.
     * 2. Ambil daftar kategori unik untuk tombol/filter kategori.
     * 3. Jika terdapat parameter 'search', saring menu yang namanya memuat kata kunci (case-insensitive).
     * 4. Jika terdapat parameter 'category', saring menu yang kategorinya cocok.
     * 5. Jika terdapat parameter 'sort', urutkan menu ('cheapest' ascending, 'most_expensive' descending).
     * 6. Kirim data ke view menus.index.
     */
    public function index(Request $request): View
    {
        // Menentukan judul halaman
        $title = 'Katalog Menu';

        // Mengambil seluruh data menu dalam bentuk Collection melalui MenuService
        $menus = MenuService::all();

        // Mengambil daftar nama kategori unik dari data menu untuk tombol filter
        $categories = $menus->pluck('category')->unique()->values();

        // Mengambil input query filter pencarian kata kunci, kategori, dan opsi pengurutan
        $search = $request->query('search');
        $category = $request->query('category');
        $sort = $request->query('sort');

        // Menyaring data menu berdasarkan pencarian nama jika parameter 'search' diisi
        if (! empty($search)) {
            $menus = $menus->filter(function (array $menu) use ($search) {
                // str_contains case-insensitive dengan memanfaatkan strtolower
                return str_contains(
                    strtolower($menu['name']),
                    strtolower(trim($search))
                );
            });
        }

        // Menyaring data menu berdasarkan kategori terpilih jika parameter 'category' diisi
        if (! empty($category)) {
            $menus = $menus->where('category', $category);
        }

        // Mengurutkan menu berdasarkan harga: termurah (asc) atau termahal (desc)
        if ($sort === 'cheapest') {
            $menus = $menus->sortBy('price')->values();
        } elseif ($sort === 'most_expensive') {
            $menus = $menus->sortByDesc('price')->values();
        }

        // Me-render view katalog menu dengan mengirimkan variabel-variabel yang diperlukan
        return view('menus.index', [
            'title' => $title,
            'menus' => $menus,
            'categories' => $categories,
            'search' => $search,
            'category' => $category,
            'sort' => $sort,
        ]);
    }

    /**
     * Menampilkan halaman detail suatu menu berdasarkan ID.
     * Jika ID menu tidak ditemukan dalam data JSON, sistem akan mengembalikan HTTP 404 (Not Found).
     */
    public function show(int $id): View
    {
        // Mencari data menu tunggal melalui MenuService berdasarkan ID
        $menu = MenuService::find($id);

        // Jika menu tidak ditemukan, hentikan proses dan tampilkan halaman 404 Not Found
        if (! $menu) {
            abort(404, 'Menu yang Anda cari tidak ditemukan.');
        }

        // Menentukan judul halaman berdasarkan nama menu
        $title = 'Detail Menu - '.$menu['name'];

        // Me-render view detail menu
        return view('menus.show', [
            'title' => $title,
            'menu' => $menu,
        ]);
    }
}
