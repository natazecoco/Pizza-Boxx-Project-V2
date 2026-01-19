<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Http\Controllers\Controller;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('pages.customer.dashboard', compact('orders'));
    }

    public function show($id)
    {
        // Ambil data detail pesanan, piza yang dibeli, dan lokasi cabangnya
        $order = Order::with(['location', 'orderItems.product'])
                    ->where('user_id', auth()->id())
                    ->findOrFail($id);

        return view('pages.customer.show', compact('order'));
    }
    public function complete($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        // Keamanan: Hanya izinkan jika statusnya sudah 'delivered'
        if ($order->status !== 'delivered') {
            return back()->with('error', 'Pesanan belum bisa diselesaikan.');
        }

        $order->status = 'completed';
        $order->save();

        return back()->with('success', 'Terima kasih! Pesanan Anda telah selesai.');
    }
}