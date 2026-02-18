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
        $bestSellers = Product::where('is_available', true)
            ->where('is_best_seller', true)
            ->take(3)
            ->get();

        return view('pages.Front.home', compact('bestSellers'));
    }
}