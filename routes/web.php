<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JenisObatController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\MetodeBayarController;
use App\Http\Controllers\JenisPengirimanController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\KeranjangController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin,pemilik'])->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
    });

    Route::resource('jenis-obat', JenisObatController::class);
    Route::resource('obat', ObatController::class);

    Route::resource('metode-bayar', MetodeBayarController::class);
    Route::resource('jenis-pengiriman', JenisPengirimanController::class);
    Route::resource('distributor', DistributorController::class);

    Route::resource('pelanggan', PelangganController::class);
    Route::resource('pembelian', PembelianController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::resource('penjualan', PenjualanController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    Route::resource('pengiriman', PengirimanController::class)->only(['index', 'edit', 'update']);
    Route::patch('pengiriman/{pengiriman}/mark-arrived', [PengirimanController::class, 'markArrived'])
        ->name('pengiriman.markArrived');

    Route::resource('keranjang', KeranjangController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('keranjang/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');
});

Route::middleware(['role:admin,pemilik'])->group(function () {
    Route::resource('jenis-obat', JenisObatController::class);
    Route::resource('obat', ObatController::class);
    Route::resource('metode-bayar', MetodeBayarController::class);
    Route::resource('jenis-pengiriman', JenisPengirimanController::class);
    Route::resource('distributor', DistributorController::class);
    Route::resource('pelanggan', PelangganController::class);
});

require __DIR__ . '/auth.php';
