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
//         return view('pages.pegawai.dashboard', compact('totalOrders', 'pendingOrders', 'totalDeliveries'));
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

class PegawaiDashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung total pendapatan hari ini
        $todaySales = Order::whereDate('created_at', Carbon::today())
            ->whereIn('status', ['completed', 'delivered'])
            ->sum('total_amount');

        // 2. Hitung jumlah pesanan masuk hari ini
        $todayOrdersCount = Order::whereDate('created_at', Carbon::today())->count();

        // 3. Pesanan yang perlu segera dimasak (Pending)
        $pendingOrdersCount = Order::where('status', 'pending')->count();

        // 4. Pesanan yang sedang diantar (On Delivery)
        $onDeliveryCount = Order::where('status', 'on_delivery')->count();

        // 5. Ambil 5 pesanan terbaru untuk ditampilkan di tabel
        $recentOrders = Order::orderBy('created_at', 'desc')->take(5)->get();

        return view('pages.pegawai.dashboard', compact(
            'todaySales', 
            'todayOrdersCount', 
            'pendingOrdersCount', 
            'onDeliveryCount',
            'recentOrders'
        ));
    }
}