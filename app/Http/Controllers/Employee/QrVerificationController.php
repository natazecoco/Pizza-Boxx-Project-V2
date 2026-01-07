<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class QrVerificationController extends Controller
{
    public function showForm(Request $request)
    {
        return view('pages.pegawai.verify');
    }

    public function verify(Request $request)
    {
        $validatedData = $request->validate([
            'pin' => 'required|string|size:6',
        ]);
        
        $pin = $validatedData['pin'];

        $order = Order::where('pickup_pin', $pin)
                      ->whereIn('status', ['ready_for_delivery'])
                      ->first();
        
        if (!$order) {
            return back()->with('error', 'PIN tidak valid atau pesanan belum siap diambil.');
        }

        $order->status = 'completed';
        $order->save();

        return back()->with('success', 'Verifikasi berhasil! Status pesanan #'.$order->id.' diubah menjadi "completed".');
    }
}