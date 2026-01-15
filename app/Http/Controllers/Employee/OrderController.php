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

    public function updateOrderStatus(Request $request, Order $order)
    {
        // 1. Daftar status yang diperbolehkan di sistem kamu
        $validStatuses = [
            'pending', 'accepted', 'preparing', 'ready_for_delivery', 
            'on_delivery', 'delivered', 'completed', 'cancelled'
        ];

        // 2. Validasi input
        $request->validate([
            'status' => 'required|in:' . implode(',', $validStatuses),
        ]);

        // 3. Logika Tambahan: Proteksi agar Pickup tidak bisa "On Delivery"
        if ($order->order_type === 'pickup' && $request->status === 'on_delivery') {
            return back()->with('error', 'Pesanan pickup tidak memerlukan pengantaran.');
        }

        // 4. Simpan perubahan
        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Status pesanan #' . $order->id . ' berhasil diperbarui.');
    }


    public function show($id)
    {
        // Cari pesanan berdasarkan ID, jika tidak ada maka error 404
        $order = Order::with('orderItems')->findOrFail($id);

        // Kirim data ke halaman detail
        return view('pages.pegawai.show', compact('order'));
    }
}