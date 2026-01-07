<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;

class OrderController extends Controller
{
    /**
     * Menampilkan dashboard pegawai dengan daftar pesanan dan pengantaran.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');

        $ordersQuery = Order::with(['user', 'location', 'orderItems'])
                      ->orderBy('created_at');

        if ($status) {
            $ordersQuery->where('status', $status);
        }

        $orders = $ordersQuery->get();

        $deliveries = Delivery::with(['order', 'deliveryEmployee'])
                            ->orderByDesc('assigned_at')
                            ->get();
        
        return view('pages.pegawai.orders', compact('orders', 'deliveries'));
    }

    /**
     * Mengubah status pesanan.
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|string']);

        $order->status = $request->input('status');
        $order->save();

        return back()->with('success', 'Status pesanan berhasil diubah.');
    }

    /**
     * Mengubah status pesanan dari tombol di card (misalnya 'Terima Pesanan').
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|string']);

        $order->status = $request->input('status');
        $order->save();

        return back()->with('success', 'Status pesanan berhasil diubah.');
    }
}