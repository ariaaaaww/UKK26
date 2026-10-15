<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    $users = file_get_contents(public_path("/storage/data.json"));
    $users = collect(json_decode($users));
    return view('welcome', [
        'users' => $users
    ]);
});

// Auth Routes
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.submit');
Route::get('/register', [AuthController::class, 'registerView'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Product Routes
Route::prefix('product')->name('product.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/store', [ProductController::class, 'store'])->name('store');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('delete');
});
