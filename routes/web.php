<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckUserLevel;
use App\Http\Middleware\CheckPelanggan;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::resource('obyek-wisata', App\Http\Controllers\ObyekWisataController::class);
// Route::resource('paket-wisata', App\Http\Controllers\PaketWisataController::class);
// Route::resource('kategori-wisata', App\Http\Controllers\KategoriWisataController::class);
// Route::resource('berita', App\Http\Controllers\BeritaController::class);
// Route::resource('penginapan', App\Http\Controllers\PenginapanController::class);
// Route::resource('reservasi', App\Http\Controllers\ReservasiController::class);


// Registrasi (Hanya untuk Pelanggan)
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'loginUser'])->name('login-user');
    Route::get('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'registerUser'])->name('register-user');
});                    

// Logout (Hanya bisa diakses oleh user yang sudah login)
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->level === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($user->level === 'bendahara') {
        return redirect()->route('bendahara.dashboard');
    }

    return redirect()->back()->withErrors('Unauthorized access.');
})->middleware('auth')->name('dashboard');

Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])
    ->middleware(['auth',  CheckUserLevel::class . ':admin'])
    ->name('admin.dashboard');

Route::get('/bendahara', [App\Http\Controllers\BendaharaController::class, 'index'])
    ->middleware(['auth', CheckUserLevel::class . ':bendahara'])
    ->name('bendahara.dashboard');

Route::get('/owner', [App\Http\Controllers\OwnerController::class, 'index'])
    ->middleware(['auth', CheckUserLevel::class . ':owner'])
    ->name('owner');

// Route::get('/profilepelanggan', [App\Http\Controllers\PelangganController::class, 'profilePelanggan'])
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('auth')->group(function () {
    // Users Routes
    Route::prefix('user-manage')->group(function () {
        Route::get('/', [App\Http\Controllers\UsersController::class, 'index'])->name('user_manage');
        Route::get('/create', [App\Http\Controllers\UsersController::class, 'create'])->name('user_create');
        Route::post('/', [App\Http\Controllers\UsersController::class, 'store'])->name('user_store');
        Route::get('/{id}/edit', [App\Http\Controllers\UsersController::class, 'edit'])->name('user_edit');
        Route::put('/{id}', [App\Http\Controllers\UsersController::class, 'update'])->name('user_update');
        Route::delete('/{id}', [App\Http\Controllers\UsersController::class, 'destroy'])->name('user_destroy');
        Route::get('/{id}/detail', [App\Http\Controllers\UsersController::class, 'show'])->name('user_show');
    });

    // Reservasi Routes
    Route::prefix('reservasi')->group(function() {
        Route::get('/', [ App\Http\Controllers\ReservasiController::class, 'index'])->name('reservasi_manage');
        Route::get('/create', [ App\Http\Controllers\ReservasiController::class, 'create'])->name('reservasi_create');
        Route::post('/', [ App\Http\Controllers\ReservasiController::class, 'store'])->name('reservasi_store');
        Route::get('/{reservasi}', [ App\Http\Controllers\ReservasiController::class, 'show'])->name('reservasi_show');
        Route::get('/{reservasi}/edit', [ App\Http\Controllers\ReservasiController::class, 'edit'])->name('reservasi_edit');
        Route::put('/{reservasi}', [ App\Http\Controllers\ReservasiController::class, 'update'])->name('reservasi_update');
        Route::delete('/{reservasi}', [ App\Http\Controllers\ReservasiController::class, 'destroy'])->name('reservasi_destroy');
        
        // API for pending reservations
        Route::get('/api/pending', [ App\Http\Controllers\ReservasiController::class, 'getPendingReservations'])->name('reservasi_pending');
    });

    // Penginapan Routes
    Route::prefix('penginapan')->group(function () {
        Route::get('/', [App\Http\Controllers\PenginapanController::class, 'index'])->name('penginapan_manage');
        Route::get('/create', [App\Http\Controllers\PenginapanController::class, 'create'])->name('penginapan_create');
        Route::post('/', [App\Http\Controllers\PenginapanController::class, 'store'])->name('penginapan_store');
        Route::get('/{id}/edit', [App\Http\Controllers\PenginapanController::class, 'edit'])->name('penginapan_edit');
        Route::put('/{id}', [App\Http\Controllers\PenginapanController::class, 'update'])->name('penginapan_update');
        Route::delete('/{id}', [App\Http\Controllers\PenginapanController::class, 'destroy'])->name('penginapan_destroy');
        Route::get('/{id}/detail', [App\Http\Controllers\PenginapanController::class, 'show'])->name('penginapan_show');
    });

    // Objek Wisata Routes
    Route::prefix('objek-wisata')->group(function () {
        Route::get('/', [App\Http\Controllers\ObyekWisataController::class, 'index'])->name('objek_wisata_manage');
        Route::get('/create', [App\Http\Controllers\ObyekWisataController::class, 'create'])->name('objek_wisata_create');
        Route::post('/', [App\Http\Controllers\ObyekWisataController::class, 'store'])->name('objek_wisata_store');
        Route::get('/{id}/edit', [App\Http\Controllers\ObyekWisataController::class, 'edit'])->name('objek_wisata_edit');
        Route::put('/{id}', [App\Http\Controllers\ObyekWisataController::class, 'update'])->name('objek_wisata_update');
        Route::delete('/{id}', [App\Http\Controllers\ObyekWisataController::class, 'destroy'])->name('objek_wisata_destroy');
        Route::get('/{id}/detail', [App\Http\Controllers\ObyekWisataController::class, 'show'])->name('objek_wisata_show');
    });

    // Kategori Wisata Routes
    Route::resource('kategori-wisata', App\Http\Controllers\KategoriWisataController::class)->except(['show'])->names([
        'index' => 'kategori_wisata_manage',
        'create' => 'kategori_wisata_create',
        'store' => 'kategori_wisata_store',
        'edit' => 'kategori_wisata_edit',
        'update' => 'kategori_wisata_update',
        'destroy' => 'kategori_wisata_destroy',
    ]);

    // Berita Routes
    Route::prefix('news')->group(function () {
        Route::get('/', [App\Http\Controllers\BeritaController::class, 'index'])->name('berita_manage');
        Route::get('/create', [App\Http\Controllers\BeritaController::class, 'create'])->name('berita_create');
        Route::post('/', [App\Http\Controllers\BeritaController::class, 'store'])->name('berita_store');
        Route::get('/{id}/edit', [App\Http\Controllers\BeritaController::class, 'edit'])->name('berita_edit');
        Route::put('/{id}', [App\Http\Controllers\BeritaController::class, 'update'])->name('berita_update');
        Route::delete('/{id}', [App\Http\Controllers\BeritaController::class, 'destroy'])->name('berita_destroy');
    });

    // Kategori Berita Routes
    Route::resource('kategori-berita', App\Http\Controllers\KategoriBeritaController::class)->except(['show'])->names([
        'index' => 'kategori_berita_manage',
        'create' => 'kategori_berita_create',
        'store' => 'kategori_berita_store',
        'edit' => 'kategori_berita_edit',
        'update' => 'kategori_berita_update',
        'destroy' => 'kategori_berita_destroy',
    ]);

    // Paket Wisata Routes
    Route::resource('paket-wisata', App\Http\Controllers\PaketWisataController::class)->names([
        'index' => 'paket_manage',
        'create' => 'paket_create',
        'store' => 'paket_store',
        'show' => 'paket_show',
        'edit' => 'paket_edit',
        'update' => 'paket_update',
        'destroy' => 'paket_destroy',
    ]);
});

Route::put('user-manage/{user}', [App\Http\Controllers\UsersController::class, 'update'])->name('user.update');

// Route untuk frontend
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/package', [App\Http\Controllers\HomeController::class, 'paketWisata'])->name('package');
Route::get('/package/{id}', [App\Http\Controllers\HomeController::class, 'detailPaket'])->name('detail-package');
Route::get('/stay-cation', [App\Http\Controllers\HomeController::class, 'penginapan'])->name('penginapan');
Route::get('/stay-cation/{id}', [App\Http\Controllers\HomeController::class, 'detailPenginapan'])->name('detail-penginapan');
Route::get('/wisata', [App\Http\Controllers\HomeController::class, 'obyekWisata'])->name('obyek-wisata');
Route::get('/berita', [App\Http\Controllers\HomeController::class, 'berita'])->name('berita');
Route::get('/berita/{id}', [App\Http\Controllers\HomeController::class, 'detailBerita'])->name('detail-berita');

// Route untuk reservasi
Route::post('/reservasi', [App\Http\Controllers\ReservasiController::class, 'store'])->name('reservasi.store');
Route::get('/reservasi/success/{id}', [App\Http\Controllers\ReservasiController::class, 'success'])->name('reservasi.success');
// Route::prefix('admin')->middleware(['auth', CheckUserLevel::class . ':admin'])->group(function () {
    
// });