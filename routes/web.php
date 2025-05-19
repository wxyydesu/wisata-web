<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckUserLevel;

// ==============================
// FRONTEND ROUTES
// ==============================
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'loginUser'])->name('login-user');
    Route::get('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'registerUser'])->name('register-user');

    // Forgot Password
    Route::get('/forgot-password', [App\Http\Controllers\AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\AuthController::class, 'resetPassword'])->name('password.update');
});

// Public Content
Route::get('/berita', [App\Http\Controllers\HomeController::class, 'berita'])->name('berita');
Route::get('/berita/{id}', [App\Http\Controllers\HomeController::class, 'detailBerita'])->name('detail-berita');
Route::get('/paket', [App\Http\Controllers\HomeController::class, 'paketWisata'])->name('paket');
Route::get('/paket/{id}', [App\Http\Controllers\HomeController::class, 'detailpaket'])->name('paket.detail');
Route::get('/stay-cation', [App\Http\Controllers\HomeController::class, 'penginapan'])->name('penginapan');
Route::get('/stay-cation/{id}', [App\Http\Controllers\HomeController::class, 'detailPenginapan'])->name('detail.penginapan');
Route::get('/objek-wisata', [App\Http\Controllers\HomeController::class, 'objekWisata'])->name('objekwisata.front');
Route::get('/objek-wisata/{id}', [App\Http\Controllers\HomeController::class, 'detailObjekWisata'])->name('detail.objekwisata');
// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::get('/pesanan', [App\Http\Controllers\PesananController::class, 'index'])->name('pesanan.index')->middleware('auth');
    Route::get('/pesanan/{id}', [App\Http\Controllers\PesananController::class, 'show'])->name('pesanan.detail')->middleware('auth');
    
    // Checkout & Orders
    Route::get('/checkout/{id}', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.form');
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout');
    Route::get('/pesanan', [App\Http\Controllers\PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{id}', [App\Http\Controllers\PesananController::class, 'show'])->name('pesanan.detail');
    
    // Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    // Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/pesanan/print/all', [App\Http\Controllers\PesananController::class, 'printAll'])->name('pesanan.printAll');
    Route::get('/pesanan/{id}/print', [App\Http\Controllers\PesananController::class, 'print'])->name('pesanan.print');

    Route::get('/info', [App\Http\Controllers\InfoController::class, 'index'])->name('auth.info');
    Route::put('/info/update', [App\Http\Controllers\InfoController::class, 'update'])->name('info.update');
});

Route::middleware(CheckUserLevel::class . ':pelanggan')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('pelanggan');
});

// ==============================
// DASHBOARD ROUTES
// ==============================
Route::prefix('dashboard')->middleware('auth')->group(function () {
    // Dashboard Home
    Route::get('/', function () {
        $user = Auth::user();
        return redirect()->route($user->level);
    })->name('dashboard');

    // Admin Section
    Route::middleware(CheckUserLevel::class . ':admin')->group(function () {
        Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
        
        // User Management
        Route::prefix('users')->group(function () {
            Route::get('/', [App\Http\Controllers\UsersController::class, 'index'])->name('user.index');
            Route::get('/create', [App\Http\Controllers\UsersController::class, 'create'])->name('user.create');
            Route::post('/', [App\Http\Controllers\UsersController::class, 'store'])->name('user.store');
            Route::get('/{id}/edit', [App\Http\Controllers\UsersController::class, 'edit'])->name('user.edit');
            Route::put('/{id}', [App\Http\Controllers\UsersController::class, 'update'])->name('user.update');
            Route::delete('/{id}', [App\Http\Controllers\UsersController::class, 'destroy'])->name('user.destroy');
            Route::get('/{id}/detail', [App\Http\Controllers\UsersController::class, 'show'])->name('user.show');
        });
        // Content Management
        Route::prefix('objek-wisata')->group(function () {
            Route::get('/', [App\Http\Controllers\ObyekWisataController::class, 'index'])->name('wisata.index');
            Route::get('/create', [App\Http\Controllers\ObyekWisataController::class, 'create'])->name('wisata.create');
            Route::post('/', [App\Http\Controllers\ObyekWisataController::class, 'store'])->name('wisata.store');
            Route::get('/{obyekWisata}', [App\Http\Controllers\ObyekWisataController::class, 'show'])->name('wisata.show');
            Route::get('/{obyekWisata}/edit', [App\Http\Controllers\ObyekWisataController::class, 'edit'])->name('wisata.edit');
            Route::put('/{obyekWisata}', [App\Http\Controllers\ObyekWisataController::class, 'update'])->name('wisata.update');
            Route::delete('/{obyekWisata}', [App\Http\Controllers\ObyekWisataController::class, 'destroy'])->name('wisata.destroy');
        });

        Route::prefix('news')->group(function () {
            Route::get('/', [App\Http\Controllers\BeritaController::class, 'index'])->name('berita.index');
            Route::get('/create', [App\Http\Controllers\BeritaController::class, 'create'])->name('berita.create');
            Route::post('/', [App\Http\Controllers\BeritaController::class, 'store'])->name('berita.store');
            Route::get('/{id}/edit', [App\Http\Controllers\BeritaController::class, 'edit'])->name('berita.edit');
            Route::put('/{id}', [App\Http\Controllers\BeritaController::class, 'update'])->name('berita.update');
            Route::delete('/{id}', [App\Http\Controllers\BeritaController::class, 'destroy'])->name('berita.destroy');
        });

        Route::resource('kategori-berita', App\Http\Controllers\KategoriBeritaController::class)->except(['show'])->names([
            'index' => 'kategori-berita.index',
            'create' => 'kategori-berita.create',
            'store' => 'kategori-berita.store',
            'edit' => 'kategori-berita.edit',
            'update' => 'kategori-berita.update',
            'destroy' => 'kategori-berita.destroy',
        ]);

        Route::resource('kategori-wisata', App\Http\Controllers\KategoriWisataController::class)->except(['show']);
    });

    // Bendahara Section
    Route::middleware(CheckUserLevel::class . ':bendahara')->group(function () {
        Route::get('/bendahara', [App\Http\Controllers\BendaharaController::class, 'index'])->name('bendahara');

        // Financial Reports
        Route::get('/export-pdf', [App\Http\Controllers\OwnerController::class, 'exportPdf'])->name('exportPdf');
        Route::get('/export-excel', [App\Http\Controllers\OwnerController::class, 'exportExcel'])->name('exportExcel');
        // Tambahkan route khusus untuk batch update diskon
        Route::post('diskon/update-all', [App\Http\Controllers\DiskonPaketController::class, 'updateAll'])->name('diskon.updateAll');

        Route::resource('diskon', App\Http\Controllers\DiskonPaketController::class)->names([
            'index' => 'diskon.index',
            'update' => 'diskon.update',
        ]);

        Route::resource('/bank', \App\Http\Controllers\BankController::class)->names('bank');
    });

    // Owner Section
    Route::middleware(CheckUserLevel::class . ':owner')->group(function () {
        Route::get('/owner', [App\Http\Controllers\OwnerController::class, 'index'])->name('owner');
        
        // Analytics & Reports
        Route::get('/export-pdf', [App\Http\Controllers\OwnerController::class, 'exportPdf'])->name('exportPdf');
        Route::get('/export-excel', [App\Http\Controllers\OwnerController::class, 'exportExcel'])->name('exportExcel');
    });

    // Common Dashboard Features
    Route::prefix('penginapan')->group(function () {
        Route::get('/', [App\Http\Controllers\PenginapanController::class, 'index'])->name('penginapan.index');
        Route::get('/create', [App\Http\Controllers\PenginapanController::class, 'create'])->name('penginapan.create');
        Route::post('/', [App\Http\Controllers\PenginapanController::class, 'store'])->name('penginapan.store');
        Route::get('/{penginapan}/edit', [App\Http\Controllers\PenginapanController::class, 'edit'])->name('penginapan.edit');
        Route::put('/{penginapan}', [App\Http\Controllers\PenginapanController::class, 'update'])->name('penginapan.update');
        Route::delete('/{penginapan}', [App\Http\Controllers\PenginapanController::class, 'destroy'])->name('penginapan.destroy');
        Route::get('/{penginapan}/detail', [App\Http\Controllers\PenginapanController::class, 'show'])->name('penginapan.show');
    });

    Route::resource('paket-wisata', App\Http\Controllers\PaketWisataController::class)->names([
        'index' => 'paket.index',
        'create' => 'paket.create',
        'store' => 'paket.store',
        'show' => 'paket.show',
        'edit' => 'paket.edit',
        'update' => 'paket.update',
        'destroy' => 'paket.destroy',
    ]);
    Route::delete('/paket/{id}/delete-image/{field}', [App\Http\Controllers\PaketWisataController::class, 'deleteImage'])->name('paket.deleteImage');
    Route::get('/paket/{paket}/detail', [App\Http\Controllers\OwnerController::class, 'showPaket'])->name('be-paket.detail');

    Route::prefix('reservasi')->group(function () {
        Route::get('/', [App\Http\Controllers\ReservasiController::class, 'index'])->name('reservasi.index');
        Route::get('/create', [App\Http\Controllers\ReservasiController::class, 'create'])->name('reservasi.create');
        Route::post('/', [App\Http\Controllers\ReservasiController::class, 'store'])->name('reservasi.store');
        Route::get('/{reservasi}/show', [App\Http\Controllers\ReservasiController::class, 'show'])->name('reservasi.show');
        Route::get('/{reservasi}/edit', [App\Http\Controllers\ReservasiController::class, 'edit'])->name('reservasi.edit');
        Route::put('/{reservasi}', [App\Http\Controllers\ReservasiController::class, 'update'])->name('reservasi.update');
        Route::delete('/{reservasi}', [App\Http\Controllers\ReservasiController::class, 'destroy'])->name('reservasi.destroy');
        Route::get('/api/pending', [App\Http\Controllers\ReservasiController::class, 'getPendingReservations'])->name('reservasi_pending');
        Route::put('/reservasi/{reservasi}/confirm', [App\Http\Controllers\OwnerController::class, 'confirm'])->name('reservasi.confirm');
        Route::put('/reservasi/{reservasi}/reject', [App\Http\Controllers\OwnerController::class, 'reject'])->name('reservasi.reject');
        Route::get('/reservasi/{reservasi}/detail', [App\Http\Controllers\OwnerController::class, 'showReservasi'])->name('be-reservasi.detail');
    });

    Route::get('/pesanan/{id}/print', [App\Http\Controllers\PesananController::class, 'print'])->name('pesanan.print');
    Route::get('/pesanan/{id}/transfer-proof', [App\Http\Controllers\PesananController::class, 'showTransferProof'])->name('pesanan.transfer-proof');
});