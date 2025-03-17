<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('pelanggan', App\Http\Controllers\PelangganController::class);
Route::resource('users', App\Http\Controllers\UsersController::class);
Route::resource('karyawan', App\Http\Controllers\KaryawanController::class);
Route::resource('obyek-wisata', App\Http\Controllers\ObyekWisataController::class);
Route::resource('paket-wisata', App\Http\Controllers\PaketWisataController::class);
Route::resource('kategori-wisata', App\Http\Controllers\KategoriWisataController::class);
Route::resource('berita', App\Http\Controllers\BeritaController::class);
Route::resource('penginapan', App\Http\Controllers\PenginapanController::class);
Route::resource('reservasi', App\Http\Controllers\ReservasiController::class);
