<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private function getProducts()
    {
        $products = json_decode(file_get_contents(storage_path('\app\public\data.json')), true);
        $products = collect($products);
        return $products;
    }

    public function index(Request $request)
    {
        $products = $this->getProducts();
        $cart = session()->get('cart', []);
        $total = array_sum(array_column($cart, 'qty'));

        return view('products.index', compact('products', 'total'));
    }

    public function cart()
    {
        $cartItems = session()->get('cart', []);
        $totalSebelumPPN = collect($cartItems)->sum(fn($item) => $item['harga'] * $item['qty']);

        // Cara 1 
        $totalDiskon = $totalSebelumPPN > 250000 ? $totalSebelumPPN * 0.98 : $totalSebelumPPN;
        $ppn = 0.11;
        $totalBelanja = ($totalSebelumPPN * $ppn) + $totalSebelumPPN;

        // Cara 2
        // $diskon = 0;

        // if ($totalSebelumPPN > 250000) {
        //     $diskon = $totalSebelumPPN * 0.02;
        // }
        // $totalDiskon = $totalSebelumPPN - $diskon;

        // $ppn = $totalSebelumPPN * 0.11;
        // $totalBelanja = $totalSebelumPPN + $ppn;

        // return view('products.cart', compact(
        //     'cartItems',
        //     'totalSebelumPPN',
        //     'diskon',
        //     'ppn',
        //     'totalBelanja',
        // ));

        return view('products.cart', compact(
            'cartItems',
            'totalSebelumPPN',
            'totalDiskon',
            'ppn',
            'totalBelanja',
        ));
    }

    public function addToCart(Request $request, string $productId)
    {
        // Buat convert dari 'id' ke $id
        $productId = $request->input('id');
        // Buat panggil data dari JSON
        $products = $this->getProducts();
        $selectedProduct = $products->firstWhere('id', $productId);

        if (!$selectedProduct) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        // [] array kosong ini untuk default yaitu kosong
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['qty']++;
        } else {
            $cart[$productId] = [
                'id' => $selectedProduct['id'],
                'kode' => $selectedProduct['kode'],
                'nama' => $selectedProduct['nama'],
                'harga' => $selectedProduct['harga'],
                'gambar' => $selectedProduct['gambar'],
                'qty' => 1,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }
    public function updateQty(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return redirect()->route('products.cart');
        }

        $action = $request->input('action'); // 'increase' atau 'decrease'

        if ($action === 'increase') {
            $cart[$id]['qty']++;
        } elseif ($action === 'decrease') {
            $cart[$id]['qty']--;
            if ($cart[$id]['qty'] <= 0) {
                unset($cart[$id]);
            }
        }

        session()->put('cart', $cart);

        return redirect()->route('products.cart');
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('products.cart')->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clearCart()
    {
        session()->forget('cart');

        return redirect()->route('products.cart')->with('success', 'Keranjang berhasil dikosongkan!');
    }
}
