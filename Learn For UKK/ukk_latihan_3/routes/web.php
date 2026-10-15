<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi Kafe Kopi Senja
|--------------------------------------------------------------------------
| Seluruh rute aplikasi disusun sesuai modul 14 kasus UKK:
| Katalog menu, Keranjang, Kode promo, dan Pesanan.
*/

// Katalog menu (Kasus 1 & 5)
Route::get('/', [MenuController::class, 'index'])->name('menus.index');
Route::get('/menus/{id}', [MenuController::class, 'show'])->name('menus.show');

// Keranjang (Kasus 7, 8, & 9)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{id}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

// Kode promo (Kasus 11)
Route::post('/promo', [CartController::class, 'applyPromo'])->name('promo.apply');
Route::delete('/promo', [CartController::class, 'removePromo'])->name('promo.remove');

// Pesanan (Kasus 12, 13, & 14)
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{code}', [OrderController::class, 'show'])->name('orders.show');
