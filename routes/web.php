<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

// Controller Pelanggan dari folder Customer
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\AddressController;

// Controller Pegawai dari folder Employee
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Employee\OrderController;
use App\Http\Controllers\Employee\DeliveryController;
use App\Http\Controllers\Employee\OrderDetailController;
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

    // Rute Keranjang Belanja
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/update', [CartController::class, 'update'])->name('cart.update');
        Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
    });

    // Rute Checkout
    Route::prefix('checkout')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
        Route::post('/api/validate-promo', [CheckoutController::class, 'validatePromo']);
    });
    
    // Rute Dashboard Pelanggan
    Route::middleware('auth:web')->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('user.dashboard');
        Route::get('/profile', [ProfileController::class, 'show'])->name('user.profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('user.profile.update');
        Route::get('/profile/address/create', [AddressController::class, 'create'])->name('user.address.create');
        Route::post('/profile/address', [AddressController::class, 'store'])->name('user.address.store');
        Route::delete('/profile/address/{address}', [AddressController::class, 'delete'])->name('user.address.delete');        
        // Detail & Tracking Pesanan
        Route::get('/dashboard/order/{id}', [CustomerDashboardController::class, 'show'])->name('user.order.show');
        Route::post('/dashboard/order/{id}/complete', [CustomerDashboardController::class, 'complete'])->name('user.order.complete');
    });
});

Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');

// ================================= RUTE PEGAWAI & ADMIN =================================
Route::middleware(['auth:employee', 'role:admin,employee'])->prefix('pegawai')->group(function () {
    // Dashboard Pegawai
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('pegawai.dashboard');
    // Manajemen Pesanan
    Route::get('/orders', [OrderController::class, 'index'])->name('pegawai.orders.index');
    // Update status pesanan
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateOrderStatus'])->name('pegawai.orders.update-status');

    // Detail Pesanan
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('pegawai.orders.show');
    // Update status item pesanan
    Route::get('/deliveries', [DeliveryController::class, 'index'])->name('pegawai.deliveries.index');
    // Detail Pengantaran
    Route::get('/deliveries/{id}', [DeliveryController::class, 'show'])->name('pegawai.deliveries.show');
    // Buat Pengantaran Baru
    Route::get('/deliveries/create', [DeliveryController::class, 'create'])->name('pegawai.deliveries.create');
    // Simpan Pengantaran Baru
    Route::post('/deliveries', [DeliveryController::class, 'store'])->name('pegawai.deliveries.store');
    // Detail Pengantaran
    Route::get('/deliveries/{delivery}/detail', [DeliveryController::class, 'detail'])->name('pegawai.deliveries.detail');
    // Update Pengantaran
    Route::post('/deliveries/{delivery}/update', [DeliveryController::class, 'update'])->name('pegawai.deliveries.update');
    // Verifikasi QR/PIN
    Route::get('/qr/verify', [QrVerificationController::class, 'showForm'])->name('pegawai.qr.verify.form');
    // Proses Verifikasi QR/PIN
    Route::post('/qr/verify', [QrVerificationController::class, 'verify'])->name('pegawai.qr.verify');
    // Rute logout untuk pegawai.
    Route::post('/logout', [AuthController::class, 'employeeLogout'])->name('pegawai.logout');
});

// ================================= RUTE KHUSUS ADMIN =================================
// Route::middleware(['auth:employee', 'role:admin'])->prefix('admin')->group(function () {
//     Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('filament.admin.pages.dashboard');
// });