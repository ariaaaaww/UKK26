<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $title = 'Menampilkan produk';
        $product = [
            [

            ]
        ];

        return view('product.index', [
            'title' => $title,
        ]);
    }

}
