<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

// Controller Pelanggan / Member (Butuh Login)
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\AddressController;

// Controller Publik (Tanpa Login)
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\MenuController;

// Controller Pegawai dari folder Employee
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Employee\OrderController;
use App\Http\Controllers\Employee\DeliveryController;
use App\Http\Controllers\Employee\OrderDetailController;
use App\Http\Controllers\Employee\QrVerificationController;

/*
|--------------------------------------------------------------------------
| 1. AUTHENTIKASI
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/login/pegawai', [AuthController::class, 'showLoginForm'])->defaults('type', 'employee')->name('pegawai.login');
Route::post('/login/pegawai', [AuthController::class, 'login'])->name('pegawai.login');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 2. RUTE PUBLIK (FRONT PAGE)
|--------------------------------------------------------------------------
*/
// Halaman Utama, About, dan Contact sekarang dikelola PageController
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact-send', [PageController::class, 'contactSend'])->name('contact.send');


// Katalog Menu & Detail Produk
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{product}', [MenuController::class, 'show'])->name('menu.show');

/*
|--------------------------------------------------------------------------
| 3. ALUR TRANSAKSI (KERANJANG, CHECKOUT, API INTERNAL)
|--------------------------------------------------------------------------
*/
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// API INTERNAL (Untuk AJAX di halaman Checkout)
// Kita keluarkan dari prefix 'checkout' agar URL-nya pas dengan JS: /api/...
Route::post('/api/check-delivery', [CheckoutController::class, 'checkDelivery']);
Route::post('/api/validate-promo', [CheckoutController::class, 'validatePromo']);

/*
|--------------------------------------------------------------------------
| 4. AREA PELANGGAN (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->group(function () {
    // KELOMPOK CHECKOUT
    Route::prefix('checkout')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
    });

    // Dashboard & History
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/dashboard/order/{order}', [CustomerDashboardController::class, 'show'])->name('user.order.show');
    Route::post('/dashboard/order/{order}/complete', [CustomerDashboardController::class, 'complete'])->name('user.order.complete');

    // Pengaturan Profil & Alamat
    Route::get('/profile', [ProfileController::class, 'show'])->name('user.profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::get('/profile/address/create', [AddressController::class, 'create'])->name('user.address.create');
    Route::post('/profile/address', [AddressController::class, 'store'])->name('user.address.store');
    Route::put('/profile/address/{address}', [AddressController::class, 'update'])->name('user.address.update');
    Route::delete('/profile/address/{address}', [AddressController::class, 'delete'])->name('user.address.delete');
    Route::post('/address/{id}/set-primary', [AddressController::class, 'setPrimary'])->name('user.address.set-primary');
});

/*
|--------------------------------------------------------------------------
| 5. AREA PEGAWAI & ADMIN (WAJIB LOGIN PEGAWAI)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:employee', 'role:admin,employee'])->prefix('pegawai')->group(function () {
    // Dashboard Pegawai
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('pegawai.dashboard');
    
    // Manajemen Pesanan
    Route::get('/orders', [OrderController::class, 'index'])->name('pegawai.orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('pegawai.orders.show');
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateOrderStatus'])->name('pegawai.orders.update-status');

    // Manajemen Pengantaran (Delivery)
    Route::get('/deliveries', [DeliveryController::class, 'index'])->name('pegawai.deliveries.index');
    Route::get('/deliveries/create', [DeliveryController::class, 'create'])->name('pegawai.deliveries.create');
    Route::post('/deliveries', [DeliveryController::class, 'store'])->name('pegawai.deliveries.store');
    Route::get('/deliveries/{delivery}/detail', [DeliveryController::class, 'detail'])->name('pegawai.deliveries.detail');
    Route::post('/deliveries/{delivery}/update', [DeliveryController::class, 'update'])->name('pegawai.deliveries.update');
    Route::get('/deliveries/{id}', [DeliveryController::class, 'show'])->name('pegawai.deliveries.show');

    // Verifikasi QR & PIN
    Route::get('/qr/verify', [QrVerificationController::class, 'showForm'])->name('pegawai.qr.verify.form');
    Route::post('/qr/verify', [QrVerificationController::class, 'verify'])->name('pegawai.qr.verify');

    // Logout Pegawai
    Route::post('/logout', [AuthController::class, 'employeeLogout'])->name('pegawai.logout');
});