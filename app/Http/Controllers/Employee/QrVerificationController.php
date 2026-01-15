<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class QrVerificationController extends Controller
{
    // Menampilkan form verifikasi QR/PIN
    public function showForm(Request $request)
    {
        $order = Order::findOrFail($request->query('order_id'));
        return view('pages.pegawai.verify', compact('order'));
    }

    // Memproses verifikasi QR/PIN
    public function verify(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'pin' => 'required|string|size:6', // Asumsi PIN 6 digit
        ]);

        $order = Order::find($request->order_id);

        // Cek apakah PIN cocok (Asumsi kolom di database namanya pickup_pin)
        if ($order->pickup_pin === $request->pin) {
            $order->update(['status' => 'completed']);
            // return redirect()->route('pegawai.dashboard')->with('success', 'Verifikasi berhasil! Pesanan selesai.');

            return response()->json([
            'success' => true,
            'message' => 'Verifikasi berhasil! Pesanan selesai.'
        ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'PIN yang Anda masukkan salah. Silahkan coba lagi'
        ], 422);

        // return back()->with('error', 'PIN yang dimasukkan salah. Silakan coba lagi.');
    }
}