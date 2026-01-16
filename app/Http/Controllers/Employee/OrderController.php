<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan yang difilter berdasarkan lokasi pegawai.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status');

        // --- FILTER SAKTI DIMULAI ---
        // Kita mulai query Order
        $ordersQuery = Order::with(['user', 'location', 'orderItems']);

        // JIKA yang login BUKAN Admin, maka WAJIB filter berdasarkan location_id si pegawai
        if (!$user->hasRole('admin')) {
            $ordersQuery->where('location_id', $user->location_id);
        }
        // --- FILTER SAKTI SELESAI ---

        $ordersQuery->orderBy('created_at');

        if ($status) {
            $ordersQuery->where('status', $status);
        }

        $orders = $ordersQuery->get();

        // --- FILTER SAKTI UNTUK PENGANTARAN ---
        $deliveriesQuery = Delivery::with(['order', 'deliveryEmployee']);

        if (!$user->hasRole('admin')) {
            // Karena location_id ada di tabel orders, kita gunakan whereHas
            $deliveriesQuery->whereHas('order', function($q) use ($user) {
                $q->where('location_id', $user->location_id);
            });
        }

        $deliveries = $deliveriesQuery->orderByDesc('assigned_at')->get();
        
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
        $user = Auth::user();
        
        // Cari pesanan dengan proteksi lokasi
        $query = Order::with('orderItems');

        // Pastikan pegawai tidak bisa "mengintip" detail pesanan cabang lain lewat URL
        if (!$user->hasRole('admin')) {
            $query->where('location_id', $user->location_id);
        }

        $order = $query->findOrFail($id);

        return view('pages.pegawai.show', compact('order'));
    }
}