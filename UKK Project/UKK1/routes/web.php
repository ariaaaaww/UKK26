<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::prefix('product')->name('products.')->group(function () {
    Route::get('/cart', [ProductController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{productId}', [ProductController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{productId}', [ProductController::class, 'updateQty'])->name('cart.update');
    Route::delete('/{productId}', [ProductController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/clear', [ProductController::class, 'clearCart'])->name('cart.clear');
});
