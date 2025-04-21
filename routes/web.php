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


// Registrasi (Hanya untuk Pelanggan)
Route::get('/register', [App\Http\Controllers\AuthController::class, 'register'])->middleware('guest')->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'registerUser'])->middleware('guest')->name('register-user');

// Login
Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->middleware('guest')->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'loginUser'])->middleware('guest')->name('login-user');

// Logout (Hanya bisa diakses oleh user yang sudah login)
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth')->name('logout');
