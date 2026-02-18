<?php

namespace App\Http\Controllers\Public;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Location; 
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Kategori BESERTA Produknya (Eager Loading)
        // Kita pakai 'whereHas' supaya kategori yang kosong (gak punya produk) gak ikut muncul
        $categoriesWithProducts = Category::with(['products' => function($query) {
            $query->where('is_available', true); // Hanya ambil produk yang tersedia
        }])
        ->whereHas('products', function($query) {
            $query->where('is_available', true); // Hanya ambil kategori yang punya produk aktif
        })
        ->get();

        // 2. Logika Lokasi (Tetap Pertahankan yang Lama)
        $userLat = $request->input('lat');
        $userLng = $request->input('lng');
        $allLocations = Location::all();
        $nearestBranch = null;
        $minDistance = INF;

        if ($userLat && $userLng) {
            foreach ($allLocations as $location) {
                $distance = $this->calculateDistance($userLat, $userLng, $location->latitude, $location->longitude);
                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $nearestBranch = $location;
                }
            }
        } else {
            $nearestBranch = $allLocations->first();
        }

        $branchLocationData = null;
        if ($nearestBranch) {
            $branchLocationData = [
                'id' => $nearestBranch->id,
                'name' => $nearestBranch->name,
                'latitude' => (float)$nearestBranch->latitude,
                'longitude' => (float)$nearestBranch->longitude,
                'radius_km' => (int)($nearestBranch->delivery_radius_km ?? 5),
                'distance' => $userLat ? round($minDistance, 2) : null,
            ];
        }

        // 3. Kirim ke View (Perhatikan variabel baru: $categoriesWithProducts)
        return view('pages.Front.menu', compact('categoriesWithProducts', 'branchLocationData'));
    }

    // Rumus Haversine seperti di CheckoutController
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371; 
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) ** 2;
        return $R * (2 * atan2(sqrt($a), sqrt(1-$a)));
    }

    public function show(Product $product)
    {
        $product->load(['options', 'addons']);
        $universalCrusts = ProductOption::where('type', 'Pinggiran')->get();

        // Jalur ini sudah benar
        return view('pages.Front.product-detail', compact('product', 'universalCrusts'));
    }
}