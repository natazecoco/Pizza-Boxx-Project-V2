<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Controllers\Controller;

class PegawaiOrderDetailController extends Controller
{
    public function show($orderId)
    {
        $order = Order::with(['orderItems'])->findOrFail($orderId);
        return view('pages.pegawai.order-detail', compact('order'));
    }
}
