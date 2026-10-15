<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::put('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');