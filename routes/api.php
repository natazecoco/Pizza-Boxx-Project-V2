<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Pastikan folder 'Customer' tertulis di sini agar Laravel tidak nyasar
use App\Http\Controllers\Customer\CheckoutController; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 1. Rute API untuk Validasi Promo
Route::post('/validate-promo', [CheckoutController::class, 'validatePromo']);

// 2. Rute API untuk Cek Pengantaran (INI YANG TADI HILANG)
Route::post('/check-delivery', [CheckoutController::class, 'checkDelivery']);