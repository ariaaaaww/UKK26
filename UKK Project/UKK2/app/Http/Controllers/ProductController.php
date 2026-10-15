<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function getProducts()
    {
        $products = json_decode(file_get_contents(storage_path('app/data/products.json')), true);
        $products = collect($products);

        return $products;
    }

    public function index(Request $request)
    {
        $productsData = $this->getProducts();
        $today = Carbon::today();

        $products = collect($productsData)
            ->map(function ($product) use ($today) {
                $expDate = Carbon::parse($product['date'])->startOfMonth();
                $product['days_left'] = (int) $today->diffInMonths($expDate, true);

                return $product;
            })
            ->sortBy('date')
            ->values();

        return view('products.index', [
            'products' => $products,
        ]);
    }

    public function update(Request $request, $id)
    {
        $jsonPath = storage_path('app/data/products.json');
        $products = json_decode(file_get_contents($jsonPath), true) ?? [];

        foreach ($products as $key => $product) {
            if ($product['id'] == $id) {
                $products[$key]['name'] = $request->input('name');
                $products[$key]['code'] = $request->input('code');
                $products[$key]['date'] = $request->input('date');

                if ($request->hasFile('image')) {
                    if (!empty($product['image']) && Storage::disk('public')->exists($product['image'])) {
                        Storage::disk('public')->delete($product['image']);
                    }

                    $imagePath = $request->file('image')->store('products', 'public');
                    $products[$key]['image'] = $imagePath;
                }
                break;
            }
        }

        file_put_contents($jsonPath, json_encode($products, JSON_PRETTY_PRINT));

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }
}
