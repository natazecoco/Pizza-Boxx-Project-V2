<?php

// namespace App\Http\Controllers\Employee;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Order;
// use App\Models\Delivery;

// class PegawaiDashboardController extends Controller
// {
//     /**
//      * Menampilkan dashboard pegawai dengan ringkasan data.
//      */
//     public function index()
//     {
//         // Ambil data untuk widget: total pesanan, pesanan pending, dan total pengantaran
//         $totalOrders = Order::count();
//         $pendingOrders = Order::where('status', 'pending')->count();
//         $totalDeliveries = Delivery::count();
        
//         // Data ini akan dikirim ke tampilan dashboard
//         return view('pages.employee.dashboard', compact('totalOrders', 'pendingOrders', 'totalDeliveries'));
//     }

//     // Metode updateOrderStatus() dihapus dari sini

//     /**
//      * Mengubah status pengantaran.
//      */
//     public function updateDeliveryStatus(Request $request, Delivery $delivery)
//     {
//         $request->validate(['status' => 'required|string']);

//         $delivery->status = $request->input('status');
//         $delivery->save();
        
//         return back()->with('success', 'Status pengantaran berhasil diubah.');
//     }
// }

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil ID cabang si pegawai
        $locationId = auth()->user()->location_id; 

        // 2. Hitung total pendapatan hari ini di cabang si pegawai
        $todaySales = Order::where('location_id', $locationId)
            ->whereDate('created_at', Carbon::today())
            ->whereIn('status', ['completed', 'delivered'])
            ->sum('total_amount');

        // 3. Hitung jumlah pesanan masuk hari ini di cabang si pegawai
        $todayOrdersCount = Order::where('location_id', $locationId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // 4. Pesanan yang perlu segera dimasak (Pending) di cabang si pegawai
        $pendingOrdersCount = Order::where('location_id', $locationId)
            ->where('status', 'pending')
            ->count();

        // 5. Pesanan yang sedang diantar (On Delivery) di cabang si pegawai
        $onDeliveryCount = Order::where('location_id', $locationId)
            ->where('status', 'on_delivery')
            ->count();

        // 6. Ambil 5 pesanan terbaru di cabang si pegawai untuk ditampilkan di tabel
        $recentOrders = Order::where('location_id', $locationId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('pages.employee.dashboard', compact(
            'todaySales', 'todayOrdersCount', 'pendingOrdersCount', 'onDeliveryCount', 'recentOrders'
        ));
    }
}