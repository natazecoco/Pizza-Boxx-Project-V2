<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Http\Request; // Tambahkan ini untuk menangkap data form

class PageController extends Controller
{
    public function home()
    {
        $bestSellers = Product::where('is_available', true)
            ->where('is_best_seller', true)
            ->take(3)
            ->get();

        return view('pages.Front.home', compact('bestSellers'));
    }

    public function about()
    {
        return view('pages.Front.about');
    }

    public function contact()
    {
        $outlets = Location::all();
        return view('pages.Front.contact', compact('outlets'));
    }

    /**
     * Logika untuk menangkap form dari halaman Contact
     */
    public function contactSend(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|min:10',
        ]);

        // 2. Simulasi/Logika tambahan (misal simpan ke DB atau Kirim Email)
        // Sementara kita pakai Opsi C (Langsung Redirect dengan Success)

        // 3. Kembalikan ke halaman dengan Toast Success
        return redirect()->back()->with('success', 'Pesanmu sudah kami terima! Tim Pizza Boxx akan segera menghubungimu.');
    }
}