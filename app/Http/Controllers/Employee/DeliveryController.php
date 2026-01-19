<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    // 1. Menampilkan Semua Daftar Antrean
    public function index()
    {
        $deliveries = Order::where('order_type', 'delivery')
            // Ambil yang siap diantar (ready_for_delivery) dan yang sedang di jalan (on_delivery)
            ->whereIn('status', ['ready_for_delivery', 'on_delivery']) 
            ->orderBy('created_at', 'asc') // FIFO: yang lama di atas
            ->get();

        return view('pages.employee.deliveries', compact('deliveries'));
    }

    // 2. Menampilkan Detail Satu Pesanan (Akan kita gunakan nanti)
    public function show($id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
        return view('pages.employee.delivery-detail', compact('order'));
    }
}