<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PegawaiDeliveryController extends Controller
{
    // 1. Menampilkan Semua Daftar Antrean
    public function index()
    {
        $deliveries = Order::where('order_type', 'delivery')
            ->whereIn('status', ['accepted', 'preparing', 'on_delivery'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Pastikan path view ini sesuai folder kamu: pages/pegawai/deliveries.blade.php
        return view('pages.pegawai.deliveries', compact('deliveries'));
    }

    // 2. Menampilkan Detail Satu Pesanan (Akan kita gunakan nanti)
    public function show($id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
        return view('pages.pegawai.delivery-detail', compact('order'));
    }
}