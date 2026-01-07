<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;

class PegawaiDeliveryController extends Controller
{
    /**
     * Menampilkan daftar semua pengantaran.
     */
    public function index()
    {
        // Mengambil semua data pengantaran dengan data pesanan terkait
        $deliveries = Delivery::with(['order', 'deliveryEmployee'])->orderBy('assigned_at', 'desc')->get();
        return view('pages.pegawai.deliveries', compact('deliveries'));
    }

    /**
     * Menampilkan formulir untuk membuat pengantaran baru.
     */
    public function create()
    {
        // Mengambil pesanan yang siap diantar
        $orders = Order::where('status', 'ready_for_delivery')->get();
        // Mengambil semua pegawai (yang berhak jadi kurir)
        $employees = User::role('employee')->get();
        return view('pages.pegawai.deliveries-create', compact('orders', 'employees'));
    }

    /**
     * Menyimpan pengantaran baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'delivery_employee_id' => 'required|exists:users,id',
            'status' => 'required|string',
        ]);
        
        $delivery = Delivery::create($validated);
        
        // Perbarui status pesanan terkait
        $delivery->order->update(['status' => $validated['status']]);

        return redirect()->route('pegawai.deliveries.index')->with('success', 'Pengantaran berhasil dibuat.');
    }

    /**
     * Menampilkan detail pengantaran.
     */
    public function detail(Delivery $delivery)
    {
        // Mengambil semua pegawai untuk dropdown update
        $employees = User::role('employee')->get();
        return view('pages.pegawai.delivery-detail', compact('delivery', 'employees'));
    }

    /**
     * Memperbarui status pengantaran dan pesanan terkait.
     */
    public function update(Request $request, Delivery $delivery)
    {
        $validated = $request->validate([
            'delivery_employee_id' => 'required|exists:users,id',
            'status' => 'required|string',
            'assigned_at' => 'nullable|date',
            'picked_up_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $delivery->update($validated);
        $delivery->order->update(['status' => $validated['status']]);
        
        return redirect()->route('pegawai.deliveries.index')->with('success', 'Pengantaran berhasil diperbarui.');
    }
}