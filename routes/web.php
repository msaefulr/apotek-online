<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisObatController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\MetodeBayarController;
use App\Http\Controllers\JenisPengirimanController;
use App\Http\Controllers\DistributorController;

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

Route::middleware(['auth'])->group(function () {
    Route::resource('jenis-obat', JenisObatController::class);
    Route::resource('obat', ObatController::class);

    Route::resource('metode-bayar', MetodeBayarController::class);
    Route::resource('jenis-pengiriman', JenisPengirimanController::class);
    Route::resource('distributor', DistributorController::class);
});

require __DIR__ . '/auth.php';
