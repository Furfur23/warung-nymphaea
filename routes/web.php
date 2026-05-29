<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes — E-Commerce UMKM Kuliner
|--------------------------------------------------------------------------
*/

// ── Katalog Produk ────────────────────────────────────────────────────────
Route::get('/', [ProductController::class, 'index'])->name('catalog.index');

// ── Keranjang Belanja ─────────────────────────────────────────────────────
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/',               [CartController::class, 'index'])->name('index');
    Route::post('/add/{id}',      [CartController::class, 'add'])->name('add');
    Route::patch('/update/{id}',  [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear',       [CartController::class, 'clear'])->name('clear');
});

// ── Checkout & Pembayaran ─────────────────────────────────────────────────
Route::prefix('checkout')->name('checkout.')->group(function () {

    // Form data pengiriman
    Route::get('/',         [CheckoutController::class, 'index'])->name('index');

    // Proses form → buat order → redirect ke Midtrans
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');

    // Halaman finish (callback redirect dari Midtrans Snap setelah bayar)
    Route::get('/finish',   [CheckoutController::class, 'finish'])->name('finish');
});
