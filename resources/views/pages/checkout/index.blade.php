<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanjutkan ke Checkout - Pizza Delivery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'pizza-red': '#e53e3e',
                        'pizza-light': '#fed7d7',
                        'pizza-dark': '#c53030'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Animation for loading */
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        .pulse {
            animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #e53e3e;
            border-radius: 10px;
        }
        
        /* Checkbox and radio styling */
        input[type="radio"]:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e");
        }
        
        /* Loading spinner */
        .spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #e53e3e;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 8px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <div class="relative mb-8 pt-4">
            <a href="{{ route('cart.index') }}" class="absolute left-0 top-0 inline-flex items-center px-4 py-2 text-md font-medium text-pizza-red 700 bg-white rounded-full shadow-md hover:bg-gray-100 transition-colors">
                <i class="fas fa-cart-plus mr-2"></i> Kembali
            </a>
            
            <!-- // Header Section -->
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-pizza-red flex items-center justify-center mb-4">
                    <i class="fas fa-shopping-cart text-white text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-pizza-red mb-2 text-center">Lanjutkan ke Checkout</h1>
                <p class="text-gray-600 text-center">Selesaikan pesanan Anda dengan mengisi informasi berikut</p>
            </div>
        </div>

        <!-- // Notification Area -->
        <div id="notification-area" class="mb-6">
            @if(session('error'))
                <div class="bg-red-500 text-white p-3 rounded-lg mb-4 text-center">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="bg-green-500 text-white p-3 rounded-lg mb-4 text-center">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-500 text-white p-3 rounded-lg mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- // Checkout Form -->
        <form id="checkoutForm" class="space-y-6" action="{{ route('checkout.process') }}" method="POST">
            @csrf
            
            <!-- // Order Summary Section -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 rounded-full bg-pizza-red flex items-center justify-center mr-3">
                        <span class="text-white font-bold">1</span>
                    </div>
                    <h2 class="text-xl font-bold text-pizza-red">Ringkasan Pesanan Anda</h2>
                </div>
                
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuantitas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="cart-items">
                            @foreach($cart as $itemKey => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($item['image_path'])
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $item['image_path']) }}" alt="{{ $item['name'] }}">
                                                @else
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('images/pizza-boxx-logo.png') }}" alt="Default Image">
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-md font-medium text-gray-800">{{ $item['name'] }}</div>
                                                @if($item['size_option'])
                                                    <div class="text-sm text-gray-500">- Ukuran: {{ $item['size_option']['name'] }}</div>
                                                @endif
                                                @if($item['crust_option'])
                                                    <div class="text-sm text-gray-500">- Pinggiran: {{ $item['crust_option']['name'] }}</div>
                                                @endif
                                                @if(!empty($item['addons']))
                                                    <div class="text-sm text-gray-500">- Tambahan:
                                                        @foreach($item['addons'] as $addon)
                                                            {{ $addon['name'] }}@if(!$loop->last), @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">{{ $item['quantity'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-lg text-pizza-red font-medium">Rp {{ number_format($item['total_price'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Customer Details Section -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 rounded-full bg-pizza-red flex items-center justify-center mr-3">
                        <span class="text-white font-bold">2</span>
                    </div>
                    <h2 class="text-xl font-bold text-pizza-red">Detail Pelanggan</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_name" class="block text-md font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="text" id="customer_name" name="customer_name" required
                                class="w-full pl-10 p-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-800 focus:ring-pizza-red focus:border-pizza-red transition-colors"
                                value="{{ old('customer_name', $user->name ?? '') }}">
                        </div>
                    </div>
                    
                    <div>
                        <label for="customer_email" class="block text-md font-medium text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="email" id="customer_email" name="customer_email"
                                class="w-full pl-10 p-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-800 focus:ring-pizza-red focus:border-pizza-red transition-colors"
                                value="{{ old('customer_email', $user->email ?? '') }}">
                        </div>
                    </div>
                    
                    <div>
                        <label for="customer_phone" class="block text-md font-medium text-gray-700 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-phone absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="tel" id="customer_phone" name="customer_phone" required
                                class="w-full pl-10 p-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-800 focus:ring-pizza-red focus:border-pizza-red transition-colors"
                                value="{{ old('customer_phone', $user->phone_number ?? '') }}">
                        </div>
                    </div>
                    
                    <div>
                        <label for="location_id" class="block text-md font-medium text-gray-700 mb-2">
                            Pilih Lokasi Toko <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-store absolute left-3 top-3.5 text-gray-400 z-10"></i>
                            <select id="location_id" name="location_id" required
                                class="w-full pl-10 p-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-800 focus:ring-pizza-red focus:border-pizza-red appearance-none transition-colors">
                                <option value="">Pilih Lokasi</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" 
                                        data-lat="{{ $location->latitude }}" 
                                        data-lng="{{ $location->longitude }}" 
                                        data-map-url="{{ $location->maps_url }}" 
                                        {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }} ({{ $location->address }})
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-3.5 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Details Section -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 rounded-full bg-pizza-red flex items-center justify-center mr-3">
                        <span class="text-white font-bold">3</span>
                    </div>
                    <h2 class="text-xl font-bold text-pizza-red">Tipe Pesanan</h2>
                </div>
                
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-6 mb-4">
                    <label class="flex-1 flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-pizza-red transition-colors" id="delivery_option_label">
                        <input type="radio" name="order_type" value="delivery" id="order_type_delivery" class="form-radio h-5 w-5 text-pizza-red" checked>
                        <div class="ml-3">
                            <span class="text-md font-medium text-gray-800">Delivery</span>
                            <p class="text-sm text-gray-500">Pesan antar ke lokasi Anda</p>
                        </div>
                        <i class="fas fa-motorcycle ml-auto text-pizza-red text-xl"></i>
                    </label>
                    
                    <label class="flex-1 flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-pizza-red transition-colors" id="pickup_option_label">
                        <input type="radio" name="order_type" value="pickup" id="order_type_pickup" class="form-radio h-5 w-5 text-pizza-red">
                        <div class="ml-3">
                            <span class="text-md font-medium text-gray-800">Pickup</span>
                            <p class="text-sm text-gray-500">Ambil pesanan di toko</p>
                        </div>
                        <i class="fas fa-walking ml-auto text-pizza-red text-xl"></i>
                    </label>
                </div>
                
                <p id="order_type_message" class="text-sm text-gray-500 bg-pizza-light p-3 rounded-lg">
                    <i class="fas fa-info-circle text-pizza-red mr-2"></i>
                    Pilih opsi 'Delivery' untuk pengantaran ke lokasi Anda, atau 'Pickup' untuk mengambil pesanan di toko.
                </p>
            </div>

            <!-- Details Section -->
            <div id="delivery_details" class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                
                <!-- Delivery Details Header -->
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 rounded-full bg-pizza-red flex items-center justify-center mr-3">
                        <span class="text-white font-bold">4</span>
                    </div>
                    <h3 id="section4_title" class="text-xl font-bold text-pizza-red">Detail Pengiriman</h3>
                </div>
                
                <!-- Location Modal for Out of Range -->
                <div id="location-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-black/60 backdrop-blur-sm px-4">
                    <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl transform transition-all scale-100">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                                <i class="fas fa-map-marker-alt text-red-600 text-3xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Luar Jangkauan</h3>
                            <p id="modal-message" class="text-gray-600 mb-6"></p>
                            
                            <div class="bg-orange-50 border border-orange-200 rounded-2xl p-4 mb-6">
                                <p class="text-sm text-orange-800 font-medium">Jangan khawatir!</p>
                                <p class="text-xs text-orange-600">Sistem akan mengalihkan ke metode <b>Pickup</b> secara otomatis dalam:</p>
                                <p id="modal-countdown" class="text-4xl font-black text-red-600 mt-2">5</p>
                            </div>
                            
                            <button type="button" onclick="closeLocationModal()" class="text-gray-400 hover:text-gray-600 text-sm font-medium underline">
                                Tutup Sekarang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Delivery Address Field -->
                <div class="mb-4">
                    <label id="address_label" for="delivery_address" class="block text-md font-medium text-gray-700 mb-2">
                        Alamat Pengiriman <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-3 top-3.5 text-gray-400"></i>
                        <textarea id="delivery_address" name="delivery_address" rows="3" readonly
                            class="w-full pl-10 p-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-800 focus:outline-none focus:ring-0 cursor-not-allowed transition-colors">Mendapatkan lokasi Anda...</textarea>
                    </div>
                    <p id="location_status" class="mt-2 text-sm text-gray-500 flex items-center">
                        <span class="spinner"></span> Mendapatkan lokasi GPS Anda...
                    </p>
                    <input type="hidden" name="latitude" id="latitude_input">
                    <input type="hidden" name="longitude" id="longitude_input">
                </div>

                <!-- Delivery Notes Field -->
                <div id="delivery_notes_container" class="mb-4">
                    <label for="delivery_notes" class="block text-md font-medium text-gray-700 mb-2">
                        Catatan Pengiriman (opsional)
                    </label>
                    <div class="relative">
                        <i class="fas fa-sticky-note absolute left-3 top-3.5 text-gray-400"></i>
                        <textarea id="delivery_notes" name="delivery_notes" rows="2"
                            class="w-full pl-10 p-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-800 focus:ring-pizza-red focus:border-pizza-red transition-colors"
                            placeholder="Contoh: Tidak ada bel, tinggalkan di depan pintu">{{ old('delivery_notes') }}</textarea>
                    </div>
                </div>

                <!-- Delivery Fee Display -->
                <div id="delivery_fee_section">
                    <label class="block text-md font-medium text-gray-700 mb-2">Biaya Pengiriman</label>
                    <div class="flex items-center bg-gray-50 p-4 rounded-lg">
                        <i class="fas fa-truck text-pizza-red text-lg mr-3"></i>
                        <p id="delivery_fee_display" class="text-lg font-semibold text-pizza-red">Rp 0</p>
                        <input type="hidden" name="delivery_fee" id="delivery_fee_input" value="0">
                    </div>
                </div>

                <!-- Pickup Store Details -->
                <div id="pickup_store_details" class="hidden mt-4">
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <h4 class="text-lg font-bold text-gray-800 mb-2">
                            <i class="fas fa-store text-pizza-red mr-2"></i>
                            Detail Toko
                        </h4>

                        <p id="store_name" class="font-medium text-gray-800">-</p>
                        <p id="store_address" class="text-sm text-gray-600">-</p>

                        <a id="store_map"
                        href="#"
                        target="_blank"
                        class="inline-flex items-center mt-3 text-pizza-red font-medium hover:underline">
                            <i class="fas fa-map-location-dot mr-2"></i>
                            Lihat di Google Maps
                        </a>

                        <p class="mt-3 text-sm text-gray-500">
                            Silakan datang ke toko dan sebutkan <b>PIN Pickup</b> saat pengambilan pesanan.
                        </p>
                    </div>
                </div>

            </div>

            <!-- // Promo Code Section -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 rounded-full bg-pizza-red flex items-center justify-center mr-3">
                        <span class="text-white font-bold">5</span>
                    </div>
                    <h2 class="text-xl font-bold text-pizza-red">Kode Promo</h2>
                </div>
                
                <div class="flex items-center mb-4">
                    <div class="relative flex-grow">
                        <i class="fas fa-ticket-alt absolute left-3 top-3.5 text-gray-400"></i>
                        <input type="text" id="promo_code" name="promo_code" placeholder="Masukkan kode promo Anda"
                            class="w-full pl-10 p-3 rounded-l-lg bg-gray-50 border border-gray-300 text-gray-800 focus:ring-pizza-red focus:border-pizza-red transition-colors"
                            value="{{ old('promo_code') }}">
                    </div>
                    <button type="button" id="apply_promo_btn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-r-lg transition-colors flex items-center">
                        <i class="fas fa-check-circle mr-2"></i> Terapkan
                    </button>
                </div>
                
                <div id="promo_message" class="text-sm p-3 rounded-lg hidden"></div>
                <input type="hidden" name="discount_amount" id="discount_amount_input" value="0">
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 rounded-full bg-pizza-red flex items-center justify-center mr-3">
                        <span class="text-white font-bold">6</span>
                    </div>
                    <h2 class="text-xl font-bold text-pizza-red">Metode Pembayaran</h2>
                </div>
                
                <div id="payment_options" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                </div>
            </div>

            <!-- Order Summary Section -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 rounded-full bg-pizza-red flex items-center justify-center mr-3">
                        <span class="text-white font-bold">7</span>
                    </div>
                    <h2 class="text-xl font-bold text-pizza-red">Ringkasan Total</h2>
                </div>
                
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg text-gray-700">Subtotal:</span>
                        <span id="subtotal_display" class="text-lg font-semibold text-gray-800">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                        <input type="hidden" name="subtotal_amount" id="subtotal_amount_input" value="{{ $cartTotal }}">
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-lg text-gray-700">Diskon Promo:</span>
                        <span id="discount_display" class="text-lg font-semibold text-green-600">- Rp 0</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-lg text-gray-700">Biaya Pengiriman:</span>
                        <span id="delivery_fee_summary_display" class="text-lg font-semibold text-gray-800">Rp 0</span>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-800">Total Pembayaran:</span>
                        <span id="final_total_display" class="text-xl font-extrabold text-pizza-red">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                        <input type="hidden" name="total_amount" id="total_amount_input" value="{{ $cartTotal }}">
                    </div>
                </div>
            </div>

            <!-- // Submit Button -->
            <button type="submit" class="w-full bg-pizza-red hover:bg-pizza-dark text-white font-bold py-4 px-6 rounded-full text-xl transition-colors shadow-lg flex items-center justify-center">
                <i class="fas fa-check-circle mr-2"></i> Konfirmasi Pesanan
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cartTotal = parseFloat('{{ $cartTotal }}');

            /* ================== DOM ELEMENTS ================== */
            const el = {
                orderTypeDelivery: document.getElementById('order_type_delivery'),
                orderTypePickup: document.getElementById('order_type_pickup'),
                deliveryDetails: document.getElementById('delivery_details'),
                deliveryAddress: document.getElementById('delivery_address'),
                locationId: document.getElementById('location_id'),
                locationStatus: document.getElementById('location_status'),
                latitude: document.getElementById('latitude_input'),
                longitude: document.getElementById('longitude_input'),

                pickupStoreDetails: document.getElementById('pickup_store_details'),
                addressLabel: document.getElementById('address_label'),
                section4Title: document.getElementById('section4_title'),
                deliveryNotes: document.getElementById('delivery_notes_container'),
                deliveryFeeSection: document.getElementById('delivery_fee_section'),

                subtotalDisplay: document.getElementById('subtotal_display'),
                subtotalInput: document.getElementById('subtotal_amount_input'),

                deliveryFeeDisplay: document.getElementById('delivery_fee_display'),
                deliveryFeeSummary: document.getElementById('delivery_fee_summary_display'),
                deliveryFeeInput: document.getElementById('delivery_fee_input'),

                discountInput: document.getElementById('discount_amount_input'),
                discountDisplay: document.getElementById('discount_display'),

                finalTotalDisplay: document.getElementById('final_total_display'),
                totalAmountInput: document.getElementById('total_amount_input'),

                promoCode: document.getElementById('promo_code'),
                applyPromoBtn: document.getElementById('apply_promo_btn'),
                promoMessage: document.getElementById('promo_message'),

                paymentOptions: document.getElementById('payment_options'),
                submitBtn: document.querySelector('button[type="submit"]'),

                modal: document.getElementById('location-modal'),
                modalMsg: document.getElementById('modal-message'),
                modalTimer: document.getElementById('modal-countdown')
            };

            /* ================== INIT ================== */
            el.subtotalDisplay.textContent = `Rp ${cartTotal.toLocaleString('id-ID')}`;
            el.subtotalInput.value = cartTotal;

            /* ================== TOTAL ================== */
            function updateFinalTotal() {
                const subtotal = parseFloat(el.subtotalInput.value) || 0;
                const discount = parseFloat(el.discountInput.value) || 0;
                const fee = parseFloat(el.deliveryFeeInput.value) || 0;

                const total = subtotal - discount + fee;
                el.finalTotalDisplay.textContent = `Rp ${total.toLocaleString('id-ID')}`;
                el.totalAmountInput.value = total;
            }

            function updateDeliveryFee(fee) {
                el.deliveryFeeInput.value = fee;
                el.deliveryFeeDisplay.textContent = `Rp ${fee.toLocaleString('id-ID')}`;
                el.deliveryFeeSummary.textContent = `Rp ${fee.toLocaleString('id-ID')}`;
                updateFinalTotal();
            }

            /* ================== PAYMENT ================== */
            function renderPayment(type) {
                el.paymentOptions.innerHTML = type === 'delivery'
                    ? `
                    <label><input type="radio" name="payment_method" value="cash_on_delivery" checked> Tunai ke Kurir</label>
                    `
                    : `
                    <label><input type="radio" name="payment_method" value="cash_on_pickup" checked> Tunai di Kasir</label>
                    <label><input type="radio" name="payment_method" value="card_on_pickup"> Kartu</label>
                    `;
            }

            /* ================== LOCATION (SERVER VALIDATION) ================== */
            function validateLocation() {
                if (!el.locationId.value) {
                    el.locationStatus.innerHTML = '<span class="text-orange-500">Pilih lokasi toko</span>';
                    return;
                }

                if (!navigator.geolocation) return;

                el.locationStatus.innerHTML = '<span class="spinner"></span> Mengecek jarak...';

                navigator.geolocation.getCurrentPosition(pos => {
                    fetch('/api/check-delivery', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            location_id: el.locationId.value,
                            latitude: pos.coords.latitude,
                            longitude: pos.coords.longitude
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.allowed) {
                            el.modalMsg.innerHTML = data.message;
                            el.modal.classList.remove('hidden');

                            let c = 5;
                            el.modalTimer.innerText = c;
                            const t = setInterval(() => {
                                if (--c <= 0) {
                                    clearInterval(t);
                                    closeLocationModal();
                                    el.orderTypePickup.checked = true;
                                    updateVisibility();
                                }
                                el.modalTimer.innerText = c;
                            }, 1000);
                        } else {
                            el.latitude.value = pos.coords.latitude;
                            el.longitude.value = pos.coords.longitude;
                            el.deliveryAddress.value = 'Lokasi terverifikasi GPS';
                            updateDeliveryFee(data.delivery_fee);
                            el.locationStatus.innerHTML = `<span class="text-green-600">${data.message}</span>`;
                        }
                    });
                });
            }

            /* ================== VISIBILITY ================== */
            function updateVisibility() {
                if (el.orderTypeDelivery.checked) {
                    // Delivery Selected
                    el.section4Title.textContent = 'Detail Pengiriman';
                    // el.deliveryDetails.classList.remove('hidden');

                    el.deliveryAddress.parentElement.parentElement.classList.remove('hidden');
                    el.locationStatus.classList.remove('hidden');
                    el.deliveryNotes.classList.remove('hidden');
                    el.deliveryFeeSection.classList.remove('hidden');

                    // PICKUP: sembunyikan
                    el.pickupStoreDetails.classList.add('hidden');
                    renderPayment('delivery');
                    validateLocation();

                } else {
                    // el.deliveryDetails.classList.add('hidden');
                    el.section4Title.textContent = 'Detail Pickup';

                    // DELIVERY: sembunyikan
                    el.deliveryAddress.parentElement.parentElement.classList.add('hidden');
                    el.locationStatus.classList.add('hidden');
                    el.deliveryNotes.classList.add('hidden');
                    el.deliveryFeeSection.classList.add('hidden');

                    // PICKUP: tampilkan
                    el.pickupStoreDetails.classList.remove('hidden');

                    updateDeliveryFee(0);
                    renderPayment('pickup');
                    fillPickupStoreInfo();
                }
            }

            function fillPickupStoreInfo() {
                const selected = el.locationId.options[el.locationId.selectedIndex];
                if (!selected.value) return;

                const name = selected.text.split('(')[0].trim();
                const address = selected.text.split('(')[1]?.replace(')', '') || '';

                // AMBIL DATA DARI TITIPAN HTML TADI
                const dbMapUrl = selected.getAttribute('data-map-url');

                document.getElementById('store_name').textContent = name;
                document.getElementById('store_address').textContent = address;

                // Pasang linknya ke tombol maps
                const mapBtn = document.getElementById('store_map');
                
                if (dbMapUrl && dbMapUrl !== "null" && dbMapUrl !== "") {
                    // Jika di Admin Panel sudah diisi, pakai link resmi itu
                    mapBtn.href = dbMapUrl;
                } else {
                    // Jika belum diisi, pakai cara cadangan (search manual)
                    mapBtn.href = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(selected.text)}`;
                }
            }


            /* ================== PROMO ================== */
            el.applyPromoBtn.addEventListener('click', () => {
                fetch('/api/validate-promo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        promo_code: el.promoCode.value,
                        subtotal: el.subtotalInput.value
                    })
                })
                .then(r => r.json())
                .then(d => {
                    if (!d.success) {
                        el.discountInput.value = 0;
                        el.discountDisplay.textContent = '- Rp 0';
                        el.promoMessage.textContent = d.message;
                        return updateFinalTotal();
                    }
                    el.discountInput.value = d.discount_amount;
                    el.discountDisplay.textContent = `- Rp ${d.discount_amount.toLocaleString('id-ID')}`;
                    el.promoMessage.textContent = 'Promo diterapkan';
                    updateFinalTotal();
                });
            });

            /* ================== EVENTS ================== */
            el.orderTypeDelivery.addEventListener('change', updateVisibility);
            el.orderTypePickup.addEventListener('change', updateVisibility);
            el.locationId.addEventListener('change', updateVisibility);

            updateVisibility();
        });

        function closeLocationModal() {
            document.getElementById('location-modal').classList.add('hidden');
        }
    </script>

</body>
</html>