<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        $ordersQuery->where(function($query) {
            // Tampilkan semua pesanan yang statusnya BELUM selesai
            $query->whereNotIn('status', ['completed', 'delivered', 'cancelled'])
            // ATAU tampilkan pesanan yang SUDAH selesai/batal tapi hanya yang diupdate HARI INI
                  ->orWhere(function($q) {
                      $q->whereIn('status', ['completed', 'delivered', 'cancelled'])
                        ->whereDate('updated_at', Carbon::today());
                  });
        });

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
        
        return view('pages.employee.orders', compact('orders', 'deliveries'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        // 1. Daftar status yang diperbolehkan di sistem kamu
        $validStatuses = [
            'pending', 'accepted', 'preparing', 'ready_for_delivery', 
            'ready_for_pickup', 'on_delivery', 'delivered', 'completed', 'cancelled'
        ];

        // 2. Validasi input
        $request->validate([
            'status' => 'required|in:' . implode(',', $validStatuses),
        ]);

        // 3. Logika Tambahan: Proteksi Alur Berdasarkan Tipe Pesanan
        // Proteksi: Jika tipe Pickup, dilarang masuk ke status pengiriman
        if ($order->order_type === 'pickup' && in_array($request->status, ['ready_for_delivery', 'on_delivery', 'delivered'])) {
            return back()->with('error', 'Pesanan pickup tidak memerlukan pengantaran.');
        }

        // Proteksi: Jika tipe Delivery, dilarang masuk ke status 'ready_for_pickup'
        if ($order->order_type === 'delivery' && $request->status === 'ready_for_pickup') {
            return back()->with('error', 'Gunakan status Ready for Delivery untuk pesanan antar.');
        }

        // 4. Simpan perubahan
        $order->status = $request->status;
        $order->save();

        // 5. Jika status baru adalah 'on_delivery', buat entri di tabel deliveries
        if ($request->redirect_type === 'to_detail') {
            return redirect()->route('pegawai.deliveries.show', $order->id)
                            ->with('success', 'Hati-hati di jalan! Navigasi telah dibuka.');
        }

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

        return view('pages.employee.show', compact('order'));
    }
}