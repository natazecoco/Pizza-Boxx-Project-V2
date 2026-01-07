<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;

class PegawaiDashboardController extends Controller
{
    /**
     * Menampilkan dashboard pegawai dengan ringkasan data.
     */
    public function index()
    {
        // Ambil data untuk widget: total pesanan, pesanan pending, dan total pengantaran
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalDeliveries = Delivery::count();
        
        // Data ini akan dikirim ke tampilan dashboard
        return view('pages.pegawai.dashboard', compact('totalOrders', 'pendingOrders', 'totalDeliveries'));
    }

    // Metode updateOrderStatus() dihapus dari sini

    /**
     * Mengubah status pengantaran.
     */
    public function updateDeliveryStatus(Request $request, Delivery $delivery)
    {
        $request->validate(['status' => 'required|string']);

        $delivery->status = $request->input('status');
        $delivery->save();
        
        return back()->with('success', 'Status pengantaran berhasil diubah.');
    }
}