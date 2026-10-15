<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Home Page route
Route::get('/', [ProductController::class, 'index'])->name('products.index');

// Cart Page Route 
Route::get('/cart', [ProductController::class, 'cart'])->name('products.cart');
Route::post('/cart/add/{productId}', [ProductController::class, 'addToCart'])->name('products.cart.add');
Route::post('/cart/update/{productId}', [ProductController::class, 'updateQty'])->name('products.cart.update');
Route::delete('/{productId}', [ProductController::class, 'removeFromCart'])->name('products.cart.remove');
Route::post('/cart/clear', [ProductController::class, 'clearCart'])->name('products.cart.clear');