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

Route::resource('obyek-wisata', App\Http\Controllers\ObyekWisataController::class);
Route::resource('paket-wisata', App\Http\Controllers\PaketWisataController::class);
Route::resource('kategori-wisata', App\Http\Controllers\KategoriWisataController::class);
Route::resource('berita', App\Http\Controllers\BeritaController::class);
Route::resource('penginapan', App\Http\Controllers\PenginapanController::class);
Route::resource('reservasi', App\Http\Controllers\ReservasiController::class);


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
    Route::resource('user_manage', App\Http\Controllers\UsersController::class)->middleware(['auth', CheckUserLevel::class . ':admin'])->names([
        'index' => 'user_manage',
        'create' => 'user_create',
        'edit' => 'user_edit',
        'destroy' => 'user_destroy',
        'store' => 'user_store',
        'update' => 'user_update',
    ]);

    // Reservasi Routes
    Route::resource('reservasi', App\Http\Controllers\ReservasiController::class)->names([
        'index' => 'reservasi_manage',
        'create' => 'reservasi_create',
        'store' => 'reservasi_store',
        'show' => 'reservasi_show',
        'edit' => 'reservasi_edit',
        'update' => 'reservasi_update',
        'destroy' => 'reservasi_destroy',
    ]);

    // Penginapan Routes
    Route::resource('penginapan', App\Http\Controllers\PenginapanController::class)->names([
        'index' => 'penginapan_manage',
        'create' => 'penginapan_create',
        'store' => 'penginapan_store',
        'show' => 'penginapan_show',
        'edit' => 'penginapan_edit',
        'update' => 'penginapan_update',
        'destroy' => 'penginapan_destroy',
    ]);

    // Objek Wisata Routes
    Route::resource('objek-wisata', App\Http\Controllers\ObyekWisataController::class)->names([
        'index' => 'objek_wisata_manage',
        'create' => 'objek_wisata_create',
        'store' => 'objek_wisata_store',
        'show' => 'objek_wisata_show',
        'edit' => 'objek_wisata_edit',
        'update' => 'objek_wisata_update',
        'destroy' => 'objek_wisata_destroy',
    ]);

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
    Route::resource('berita', App\Http\Controllers\BeritaController::class)->names([
        'index' => 'berita_manage',
        'create' => 'berita_create',
        'store' => 'berita_store',
        'show' => 'berita_show',
        'edit' => 'berita_edit',
        'update' => 'berita_update',
        'destroy' => 'berita_destroy',
    ]);

    // Kategori Berita Routes
    Route::resource('kategori-berita', App\Http\Controllers\KategoriBeritaController::class)->except(['show'])->names([
        'index' => 'kategori_berita_manage',
        'create' => 'kategori_berita_create',
        'store' => 'kategori_berita_store',
        'edit' => 'kategori_berita_edit',
        'update' => 'kategori_berita_update',
        'destroy' => 'kategori_berita_destroy',
    ]);
});

Route::put('user-manage/{user}', [App\Http\Controllers\UsersController::class, 'update'])->name('user.update');


// Cart Routes
// Route::middleware(['auth'])->group(function () {
//     Route::post('/cart/add/{paket}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
//     Route::post('/cart/remove/{paket}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
//     Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
//     Route::post('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
//     Route::post('/cart/update/{paket}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
// });

// Checkout Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
});
// Route::prefix('admin')->middleware(['auth', CheckUserLevel::class . ':admin'])->group(function () {
    
// });