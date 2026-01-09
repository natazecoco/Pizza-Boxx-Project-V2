<?php

use Illuminate\Support\Facades\Route;

// Gunakan Controller yang tidak masuk ke dalam sub-folder
use App\Http\Controllers\AuthController;


// Gunakan semua Controller Pelanggan dari folder Customer
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\UserDashboardController;
use App\Http\Controllers\Customer\UserProfileController;
use App\Http\Controllers\Customer\UserAddressController;

// Gunakan semua Controller Pegawai dari folder Employee
use App\Http\Controllers\Employee\PegawaiDashboardController;
use App\Http\Controllers\Employee\OrderController;
use App\Http\Controllers\Employee\PegawaiDeliveryController;
use App\Http\Controllers\Employee\PegawaiOrderDetailController;
use App\Http\Controllers\Employee\QrVerificationController;

// ================================= AUTHENTIKASI =================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/login/pegawai', [AuthController::class, 'showLoginForm'])->defaults('type', 'employee')->name('pegawai.login');
Route::post('/login/pegawai', [AuthController::class, 'login'])->name('pegawai.login');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Rute logout untuk pelanggan.
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================================= RUTE PELANGGAN =================================
Route::group([], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/{product}', [MenuController::class, 'show'])->name('menu.show');

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/update', [CartController::class, 'update'])->name('cart.update');
        Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
    });

    Route::prefix('checkout')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
        Route::post('/api/validate-promo', [CheckoutController::class, 'validatePromo']);
    });
    
    Route::middleware('auth:web')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
        Route::get('/profile', [UserProfileController::class, 'show'])->name('user.profile');
        Route::put('/profile', [UserProfileController::class, 'update'])->name('user.profile.update');
        Route::get('/profile/address/create', [UserAddressController::class, 'create'])->name('user.address.create');
        Route::post('/profile/address', [UserAddressController::class, 'store'])->name('user.address.store');
        Route::delete('/profile/address/{address}', [UserAddressController::class, 'delete'])->name('user.address.delete');
    });
});

Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');

// ================================= RUTE PEGAWAI & ADMIN =================================
Route::middleware(['auth:employee'])->prefix('pegawai')->group(function () {
    Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');
    
    // Rute orders dipindahkan ke OrderController
    Route::get('/orders', [OrderController::class, 'index'])->name('pegawai.orders.index');
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateOrderStatus'])->name('pegawai.orders.update-status');
    
    Route::get('/deliveries', [PegawaiDeliveryController::class, 'index'])->name('pegawai.deliveries.index');
    Route::get('/deliveries/create', [PegawaiDeliveryController::class, 'create'])->name('pegawai.deliveries.create');
    Route::post('/deliveries', [PegawaiDeliveryController::class, 'store'])->name('pegawai.deliveries.store');
    Route::get('/deliveries/{delivery}/detail', [PegawaiDeliveryController::class, 'detail'])->name('pegawai.deliveries.detail');
    Route::post('/deliveries/{delivery}/update', [PegawaiDeliveryController::class, 'update'])->name('pegawai.deliveries.update');
    
    Route::get('/qr/verify', [QrVerificationController::class, 'showForm'])->name('pegawai.qr.verify.form');
    Route::post('/qr/verify', [QrVerificationController::class, 'verify'])->name('pegawai.qr.verify');

    Route::post('/logout', [AuthController::class, 'employeeLogout'])->name('pegawai.logout');
});

// ================================= RUTE KHUSUS ADMIN =================================
Route::middleware(['auth:employee', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});