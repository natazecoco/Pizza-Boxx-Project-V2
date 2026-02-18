<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout.
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        // 1. Cek Keranjang Kosong
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong, tidak bisa checkout.');
        }

        // 2. Ambil User saat ini
        $user = auth()->user();

        // [SAFETY CHECK] Pastikan user benar-benar ada.
        // Walaupun di web.php sudah ada middleware, ini perlindungan ganda.
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $cartTotal = array_sum(array_column($cart, 'total_price'));
        $locations = Location::all();

        // 3. Ambil Alamat dengan Aman
        // Kita gunakan $user->addresses. Jika user baru dan belum punya relasi, 
        // kita default-kan ke collection kosong agar tidak error di View.
        $addresses = $user->addresses ?? collect(); 
        
        // 4. Tentukan Alamat Utama (Primary)
        // Logikanya: Cari yang 'is_primary' = 1. 
        // Jika tidak ada (user lupa set), ambil alamat pertama yang ditemukan.
        // Jika tidak punya alamat sama sekali, hasilnya null (aman).
        $primaryAddress = $addresses->where('is_primary', true)->first() ?? $addresses->first();

        return view('pages.checkout.index', compact(
            'cart', 
            'cartTotal', 
            'locations', 
            'user', 
            'addresses',
            'primaryAddress'
        ));
    }

    /**
     * API untuk cek pengantaran berdasarkan lokasi dan radius.
     */
    public function checkDelivery(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $location = Location::findOrFail($request->location_id);

        $distance = $this->haversine(
            $request->latitude,
            $request->longitude,
            $location->latitude,
            $location->longitude
        );

        if ($distance > $location->delivery_radius_km) {
            return response()->json([
                'allowed' => false,
                'distance' => round($distance, 2),
                'message' => "Maaf, jarak Anda " . round($distance, 2) . " km, melebihi radius maksimal " . $location->delivery_radius_km . " km."
            ]);
        }

        return response()->json([
            'allowed' => true,
            'distance' => round($distance, 2),
            'delivery_fee' => $location->delivery_fee,
            'message' => "Kabar baik! Jarak Anda (" . round($distance, 2) . " km) masuk jangkauan."
        ]);
    }

    /**
     * Helper Rumus Haversine
     */
    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371; // Radius bumi dalam KM
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon/2) ** 2;

        return $R * (2 * atan2(sqrt($a), sqrt(1-$a)));
    }

    /**
     * Memproses pesanan dari halaman checkout.
     */
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        // UPDATE VALIDASI: Tambahkan latitude & longitude ke list
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'order_type' => 'required|in:delivery,pickup',
            'payment_method' => 'required|in:online,cash_on_delivery,card_on_pickup,cash_on_pickup',
            'location_id' => 'required|exists:locations,id',
            'delivery_address' => 'required_if:order_type,delivery|nullable|string|max:500',
            'latitude' => 'required_if:order_type,delivery|nullable|numeric', // Tambah ini
            'longitude' => 'required_if:order_type,delivery|nullable|numeric', // Tambah ini
            'delivery_notes' => 'nullable|string|max:500',
            'promo_code' => 'nullable|string|exists:promos,code',
            'subtotal_amount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'delivery_fee' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $store = Location::findOrFail($validatedData['location_id']);

        // Validasi Jarak Sisi Server
        if ($validatedData['order_type'] === 'delivery') {
            $distance = $this->haversine(
                $validatedData['latitude'], 
                $validatedData['longitude'], 
                $store->latitude, 
                $store->longitude
            );

            if ($distance > $store->delivery_radius_km) {
                return redirect()->back()->withInput()->with('error', 'Maaf, lokasi Anda di luar radius pengantaran cabang ini.');
            }
        }

        DB::beginTransaction();
        try {
            $recalculatedSubtotal = 0;

            foreach ($cart as $item) {
                // Hitung ulang harga item berdasarkan data di database
                $product = Product::findOrFail($item['product_id']);
                $itemPrice = $product->base_price;

                // Cek harga Size Option
                if (isset($item['size_option']['id'])) {
                    $itemPrice += ProductOption::find($item['size_option']['id'])->price_modifier;
                }

                // Cek harga Crust Option
                if (isset($item['crust_option']['id'])) {
                    $itemPrice += ProductOption::find($item['crust_option']['id'])->price_modifier;
                }

                // Cek harga Addons
                if (!empty($item['addons'])) {
                    foreach ($item['addons'] as $addonItem) {
                        $itemPrice += ProductAddon::find($addonItem['id'])->price;
                    }
                }

                $recalculatedSubtotal += ($itemPrice * $item['quantity']);
            }

            // Hitung diskon jika ada kode promo Sisi Server
            $discountAmount = 0;
            $promoId = null;
            if (!empty($validatedData['promo_code'])) {
                $promo = Promo::where('code', $validatedData['promo_code'])
                                 ->where('is_active', true)
                                 ->where(function($query) {
                                     $query->whereNull('start_date')
                                             ->orWhere('start_date', '<=', now());
                                 })
                                 ->where(function($query) {
                                     $query->whereNull('end_date')
                                             ->orWhere('end_date', '>=', now());
                                 })
                                 ->first();

                if ($promo && $recalculatedSubtotal >= ($promo->min_order_amount ?? 0)) {
                    if ($promo->type === 'percentage') {
                        $discountAmount = $recalculatedSubtotal * ($promo->value / 100);
                    } elseif ($promo->type === 'fixed_amount') {
                        $discountAmount = $promo->value;
                    }
                    $promoId = $promo->id;
                    if ($promo->usage_limit !== null) {
                        $promo->increment('uses');
                    }
                } else {
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('error', 'Kode promo tidak valid atau tidak memenuhi syarat!');
                }
            }

            // Validasi ulang total akhir
            $deliveryFee = $validatedData['delivery_fee'];
            $recalculatedTotal = $recalculatedSubtotal - $discountAmount + $deliveryFee;

            // Validasi kesesuaian harga sisi server dan client
            if (abs($recalculatedSubtotal - $validatedData['subtotal_amount']) > 0.01 ||
                abs($discountAmount - $validatedData['discount_amount']) > 0.01 ||
                abs($recalculatedTotal - $validatedData['total_amount']) > 0.01) {
                DB::rollBack();
                return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan perhitungan harga. Mohon coba lagi.');
            }

            // Generate Order Code
            // 1. Pecah string berdasarkan koma
            $parts = explode(',', $store->name);

            // 2. Ambil bagian kedua (index 1), bersihkan spasi, lalu ambil 3 huruf
            if (count($parts) > 1) {
                $branchNameOnly = trim($parts[1]); // Hasilnya: "Sukahati Cibinong"
                $branchInitial = strtoupper(substr($branchNameOnly, 0, 3));
            } else {
                // Fallback jika user lupa kasih koma di database
                $branchNameOnly = trim(str_ireplace('Pizza Boxx', '', $store->name));
                $branchInitial = strtoupper(substr($branchNameOnly, 0, 3));
            }

            // 3. Jaga-jaga kalau masih kosong
            if (empty($branchInitial)) {
                $branchInitial = 'PBX';
            }

            // 4. Susun Order Code
            $orderCode = 'PBX-' . $branchInitial . '-' . now()->format('dmy') . '-' . strtoupper(Str::random(4));

            // Generate PIN
            $pin = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

            // Simpan data pesanan
            $order = Order::create([
                'order_code'      => $orderCode,
                'pickup_pin'      => $pin,
                'user_id'         => auth()->id(),
                'location_id'     => $validatedData['location_id'],
                'customer_name'   => $validatedData['customer_name'],
                'customer_email'  => $validatedData['customer_email'],
                'customer_phone'  => $validatedData['customer_phone'],
                'order_type'      => $validatedData['order_type'],
                'payment_method'  => $validatedData['payment_method'],
                'status'          => 'pending',
                'delivery_address'=> $validatedData['delivery_address'],
                'delivery_notes'  => $validatedData['delivery_notes'],
                'latitude'        => $validatedData['latitude'] ?? null, 
                'longitude'       => $validatedData['longitude'] ?? null,
                'subtotal_amount' => $recalculatedSubtotal,
                'discount_amount' => $discountAmount,
                'delivery_fee'    => $deliveryFee,
                'total_amount'    => $recalculatedTotal,
                'promo_id'        => $promoId,
                'delivery_employee_id' => null,
            ]);

            foreach ($cart as $itemKey => $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem['product_id'],
                    'product_name' => $cartItem['name'],
                    'quantity' => $cartItem['quantity'],
                    'unit_price' => $cartItem['price_per_unit'],
                    'options' => array_filter([$cartItem['size_option'], $cartItem['crust_option']]),
                    'addons' => $cartItem['addons'],
                ]);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('checkout.success', ['order_id' => $order->id])->with('success', 'Pesanan Anda dengan nomor ' . $orderCode . ' berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memproses pesanan Anda. Mohon coba lagi. (Error: ' . $e->getMessage() . ')');
        }
    }

    /**
     * Menampilkan halaman sukses checkout.
     */
    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = Order::with(['orderItems.product', 'location', 'promo', 'user'])->find($orderId);

        if (!$order || (auth()->check() && $order->user_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'employee']))) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        if (!auth()->check() && !$order) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan.');
        }
        
        return view('pages.checkout.success', compact('order'));
    }

    /**
     * API untuk validasi kode promo.
     */
    public function validatePromo(Request $request)
    {
        $promoCode = $request->input('promo_code');
        $subtotal = $request->input('subtotal');

        // 1. Cari kodenya dulu (tanpa cek tanggal/aktif dulu)
        $promo = Promo::where('code', $promoCode)->first();

        // 2. Cek apakah kodenya memang tidak ada di database
        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak valid. Periksa kembali penulisan kodenya.'
            ]);
        }

        // 3. Cek apakah statusnya non-aktif
        if (!$promo->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, promo ini sudah tidak aktif.'
            ]);
        }

        // 4. Cek apakah sudah kedaluwarsa (berdasarkan end_date)
        if ($promo->end_date && \Carbon\Carbon::now()->greaterThan($promo->end_date)) {
            return response()->json([
                'success' => false,
                'message' => 'Yah, kode promo ini sudah kedaluwarsa pada ' . \Carbon\Carbon::parse($promo->end_date)->format('d M Y')
            ]);
        }

        // 5. Cek minimal belanja
        if ($subtotal < ($promo->min_order_amount ?? 0)) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal belanja untuk promo ini adalah Rp ' . number_format($promo->min_order_amount, 0, ',', '.')
            ]);
        }

        // 6. Jika semua lolos, hitung diskon
        $discountAmount = ($promo->type === 'percentage') 
            ? $subtotal * ($promo->value / 100) 
            : $promo->value;

        return response()->json([
            'success' => true,
            'message' => 'Promo berhasil diterapkan!',
            'discount_amount' => round($discountAmount, 2)
        ]);
    }
}