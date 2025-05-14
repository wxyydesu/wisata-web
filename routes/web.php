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
        return redirect()->route('admin');
    }
    if ($user->level === 'bendahara') {
        return redirect()->route('bendahara');
    }
    if ($user->level === 'owner') {
        return redirect()->route('owner');
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
    Route::prefix('user-index')->group(function () {
        Route::get('/', [App\Http\Controllers\UsersController::class, 'index'])->name('user.index');
        Route::get('/create', [App\Http\Controllers\UsersController::class, 'create'])->name('user.create');
        Route::post('/', [App\Http\Controllers\UsersController::class, 'store'])->name('user.store');
        Route::get('/{id}/edit', [App\Http\Controllers\UsersController::class, 'edit'])->name('user.edit');
        Route::put('/{id}', [App\Http\Controllers\UsersController::class, 'update'])->name('user.update');
        Route::delete('/{id}', [App\Http\Controllers\UsersController::class, 'destroy'])->name('user.destroy');
        Route::get('/{id}/detail', [App\Http\Controllers\UsersController::class, 'show'])->name('user.show');
    });

    // Reservasi Routes
    Route::prefix('reservasi')->group(function() {
        Route::get('/', [ App\Http\Controllers\ReservasiController::class, 'index'])->name('reservasi.index');
        Route::get('/create', [ App\Http\Controllers\ReservasiController::class, 'create'])->name('reservasi.create');
        Route::post('/', [ App\Http\Controllers\ReservasiController::class, 'store'])->name('reservasi.store');
        Route::get('/{reservasi}', [ App\Http\Controllers\ReservasiController::class, 'show'])->name('reservasi.show');
        Route::get('/{reservasi}/edit', [ App\Http\Controllers\ReservasiController::class, 'edit'])->name('reservasi.edit');
        Route::put('/{reservasi}', [ App\Http\Controllers\ReservasiController::class, 'update'])->name('reservasi.update');
        Route::delete('/{reservasi}', [ App\Http\Controllers\ReservasiController::class, 'destroy'])->name('reservasi.destroy');
        
        // API for pending reservations
        Route::get('/api/pending', [ App\Http\Controllers\ReservasiController::class, 'getPendingReservations'])->name('reservasi_pending');
    });

    // Penginapan Routes
    Route::prefix('penginapan')->group(function () {
        Route::get('/', [App\Http\Controllers\PenginapanController::class, 'index'])->name('penginapan.index');
        Route::get('/create', [App\Http\Controllers\PenginapanController::class, 'create'])->name('penginapan.create');
        Route::post('/', [App\Http\Controllers\PenginapanController::class, 'store'])->name('penginapan.store');
        Route::get('/{id}/edit', [App\Http\Controllers\PenginapanController::class, 'edit'])->name('penginapan.edit');
        Route::put('/{id}', [App\Http\Controllers\PenginapanController::class, 'update'])->name('penginapan.update');
        Route::delete('/{id}', [App\Http\Controllers\PenginapanController::class, 'destroy'])->name('penginapan.destroy');
        Route::get('/{id}/detail', [App\Http\Controllers\PenginapanController::class, 'show'])->name('penginapan.show');
    });

    // Objek Wisata Routes
    Route::resource('objek-wisata', App\Http\Controllers\ObyekWisataController::class)->names([
        'index' => 'wisata.index',
        'create' => 'wisata.create',
        'store' => 'wisata.store',
        'show' => 'wisata.show',
        'edit' => 'wisata.edit',
        'update' => 'wisata.update',
        'destroy' => 'wisata.destroy',
    ]);

    // Kategori Wisata Routes
    Route::resource('kategori-wisata', App\Http\Controllers\KategoriWisataController::class)->except(['show'])->names([
        'index' => 'kategori-wisata.index',
        'create' => 'kategori-wisata.create',
        'store' => 'kategori-wisata.store',
        'edit' => 'kategori-wisata.edit',
        'update' => 'kategori-wisata.update',
        'destroy' => 'kategori-wisata.destroy',
    ]);

    // Berita Routes
    Route::prefix('news')->group(function () {
        Route::get('/', [App\Http\Controllers\BeritaController::class, 'index'])->name('berita.index');
        Route::get('/create', [App\Http\Controllers\BeritaController::class, 'create'])->name('berita.create');
        Route::post('/', [App\Http\Controllers\BeritaController::class, 'store'])->name('berita.store');
        Route::get('/{id}/edit', [App\Http\Controllers\BeritaController::class, 'edit'])->name('berita.edit');
        Route::put('/{id}', [App\Http\Controllers\BeritaController::class, 'update'])->name('berita.update');
        Route::delete('/{id}', [App\Http\Controllers\BeritaController::class, 'destroy'])->name('berita.destroy');
    });

    // Kategori Berita Routes
    Route::resource('kategori-berita', App\Http\Controllers\KategoriBeritaController::class)->except(['show'])->names([
        'index' => 'kategori-berita.index',
        'create' => 'kategori-berita.create',
        'store' => 'kategori-berita.store',
        'edit' => 'kategori-berita.edit',
        'update' => 'kategori-berita.update',
        'destroy' => 'kategori-berita.destroy',
    ]);

    // Paket Wisata Routes
    Route::resource('paket-wisata', App\Http\Controllers\PaketWisataController::class)->names([
        'index' => 'paket.index',
        'create' => 'paket.create',
        'store' => 'paket.store',
        'show' => 'paket.show',
        'edit' => 'paket.edit',
        'update' => 'paket.update',
        'destroy' => 'paket.destroy',
    ]);

    Route::resource('diskon', App\Http\Controllers\PaketWisataController::class)->names([
        'index' => 'diskon.index',
        'update' => 'diskon.update',
    ]);

});

Route::put('user-index/{user}', [App\Http\Controllers\UsersController::class, 'update'])->name('user.update');

// Route export PDF/Excel untuk Owner & Bendahara (akses global)
Route::get('/export-pdf', [App\Http\Controllers\OwnerController::class, 'exportPdf'])->name('exportPdf')->middleware(['auth', CheckUserLevel::class . ':owner,bendahara']);
Route::get('/export-excel', [App\Http\Controllers\OwnerController::class, 'exportExcel'])->name('exportExcel')->middleware(['auth', CheckUserLevel::class . ':owner,bendahara']);

// FRONT END ROUTES
Route::get('/berita', [App\Http\Controllers\HomeController::class, 'berita'])->name('berita');
Route::get('/berita/{id}', [App\Http\Controllers\HomeController::class, 'detailBerita'])->name('detail-berita');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/package', [App\Http\Controllers\HomeController::class, 'paketWisata'])->name('paket');
Route::get('/package/{id}', [App\Http\Controllers\HomeController::class, 'detailpaket'])->name('detail-paket');
Route::get('/stay-cation', [App\Http\Controllers\HomeController::class, 'penginapan'])->name('penginapan');
Route::get('/stay-cation/{id}', [App\Http\Controllers\HomeController::class, 'detailPenginapan'])->name('detail-penginapan');

