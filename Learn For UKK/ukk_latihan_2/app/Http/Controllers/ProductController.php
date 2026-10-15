<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private function getProducts()
    {
        $products = json_decode(file_get_contents(storage_path('\app\public\products.json')), true);
        $products = collect($products);

        return $products;
    }

    public function index(Request $request)
    {
        $title = 'Wallpaper Buyer';
        $products = $this->getProducts();

        return view('products.index', [
            'title' => $title,
            'products' => $products,
        ]);
    }

    public function cart()
    {
        $title = 'Wallpaper Cart';
        // $cart yang berisi array kosong untuk default
        $cart = collect(session('cart', []));

        // Untuk mentotalkan uang dari jumlah barang dengan harga barang
        $totalGross = $cart->sum(function ($product) {
            return $product['price'] * ($product['qty'] ?? $product['quantity'] ?? 1);
        });

        // Jika totalNet lebih dari 250rb maka ada diskon 2%, jika tidak akan lanjut ke ppn
        $totalNet = $totalGross > 250000 ? $totalGross * 0.98 : $totalGross;
        $ppn = 0.11;
        // ppn = 11% + total
        $grandTotal = ($totalNet * $ppn) + $totalNet;

        return view('products.cart', [
            'title' => $title,
            'products' => session('cart', []),
            'grandTotal' => $grandTotal,
        ]);
    }

    public function addToCart(Request $request, string $productId)
    {
        // Buat convert dari 'id' ke $productId karena dia dari Array
        $productId = $request->input('id');
        // Buat panggil data dari JSON
        $products = $this->getProducts();

        $selectedProduct = $products->firstWhere('id', $productId);

        // [] array kosong ini untuk default yaitu kosong
        $cart = session()->get('cart', []);

        // Jika produk sudah ada. Maka, akan menjalankan quantity nya bertambah 1 dan seterusnya
        if (isset($cart[$productId])) {
            $cart[$productId]['qty']++;
            // Jika produk tidak ada. Maka, sistem akan menambahkan product sesuai dengan produk ID yang dipilih
        } else {
            $cart[$productId] = [
                'id' => $selectedProduct['id'],
                'code' => $selectedProduct['code'],
                'name' => $selectedProduct['name'],
                'price' => $selectedProduct['price'],
                'picture' => $selectedProduct['picture'],
                'qty' => 1,
            ];
        }

        // Menyimpan ke variabel cart
        session()->put('cart', $cart);

        // Browser akan berpindah ke Home pake / products/index.blade.php
        return redirect()->route('products.index');
    }

    public function updateQty(Request $request, string $productId)
    {
        // [] array kosong ini untuk default yaitu kosong
        $cart = session()->get('cart', []);

        // Mengambil nilai parameter action yang dikirim dari tombol UI form
        $action = $request->input('action');
        // Mengecek Produk Id, jika tidak ada akan mengecek quantity procuk id. Jika tidak ada maka akan menjadi 1
        $currentQty = $cart[$productId]['qty'] ?? $cart[$productId]['quantity'] ?? 1;

        // mengikuti value pada form yaitu increase yang akan menambahkan quantity
        if ($action === 'increase') {
            $cart[$productId]['qty'] = $currentQty + 1;
            // mengikuti value pada form yaitu decrease yang akan mengurangi quantity
        } elseif ($action === 'decrease') {
            $newQty = $currentQty - 1;
        }

        // Menyimpan array ke cart
        session()->put('cart', $cart);

        // Mengarahkan ke page Cart
        return redirect()->route('products.cart');
    }

    public function removeFromCart(string $productId)
    {
        // [] array kosong ini untuk default yaitu kosong
        $cart = session()->get('cart', []);

        // Mengecek apakah produk dengan ID tersebut benar-benar ada di dalam array keranjang belanja.
        if (isset($cart[$productId])) {
            // Fungsi native PHP untuk menghapus elemen/item spesifik dari array $cart berdasarkan kunci ID-nya
            unset($cart[$productId]);
            // Menyimpan kembali isi array $cart yang sudah berkurang itemnya ke dalam Session Laravel dengan kunci 'cart'.
            session()->put('cart', $cart);
        }

        // mengarahkan ke page Cart
        return redirect()->route('products.cart');
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('products.cart');
    }

}
