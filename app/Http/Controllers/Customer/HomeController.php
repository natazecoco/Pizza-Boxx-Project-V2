<?php

namespace App\Http\Controllers\Customer;


use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 4 produk terpopuler (bisa disesuaikan field popularitasnya)
        $popularPizzas = Product::where('is_available', true)
            ->orderByDesc('created_at') // Ganti dengan field popularitas yang sesuai jika ada
            ->take(4)
            ->get();

        return view('pages.home', compact('popularPizzas'));
    }
}
