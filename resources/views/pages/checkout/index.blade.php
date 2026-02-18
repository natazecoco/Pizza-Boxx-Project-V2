<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout | Pizza Boxx</title>
    
    {{-- LIBRARIES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- FONT FIGTREE (Sesuai Layout Utama) --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    
    {{-- CUSTOM STYLES --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-red': '#A41F21',
                        'brand-dark': '#111827',
                        'brand-kraft': '#fff4bd',
                    },
                    fontFamily: {
                        sans: ['Figtree', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #F8FAFC; color: #111827; }
        .bg-pizza-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 15l-5.5 11h11L30 15zm0-10l15 30H15L30 5z' fill='%23A41F21' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #111827; border-radius: 10px; }
        
        /* Brutalist Shadows */
        .brutalist-card { 
            border: 4px solid #111827; 
            box-shadow: 8px 8px 0px 0px #111827; 
            transition: all 0.2s ease;
        }
        .brutalist-card:hover { transform: translate(-2px, -2px); box-shadow: 10px 10px 0px 0px #111827; }
        
        .brutalist-input {
            border: 2px solid #111827;
            box-shadow: 2px 2px 0px 0px #111827;
        }
        .brutalist-input:focus {
            box-shadow: 4px 4px 0px 0px #111827;
            transform: translate(-1px, -1px);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 selection:bg-brand-red selection:text-white pb-32 lg:pb-12">

    {{-- HEADER MINIMALIS --}}
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b-4 border-gray-900 shadow-sm">
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl h-20 flex items-center justify-between">
            <a href="{{ route('cart.index') }}" class="group flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white border-2 border-gray-900 flex items-center justify-center group-hover:bg-brand-red group-hover:text-white transition-all shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                    <i class="fas fa-arrow-left"></i>
                </div>
                <span class="hidden sm:inline text-[10px] font-black uppercase tracking-widest text-gray-900">Batal</span>
            </a>
            
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/pizza-boxx-logo.png') }}" class="h-8 w-8 object-contain">
                <h1 class="text-2xl font-black italic text-gray-900 tracking-tighter uppercase">CHECKOUT</h1>
            </div>
            
            <div class="w-10"></div>
        </div>
    </header>

    <main class="pt-8 lg:pt-16 px-4 lg:px-8 bg-pizza-pattern">
        <div class="container mx-auto max-w-6xl">
            <form id="checkoutForm" action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="flex flex-col lg:flex-row gap-10 items-start">

                    {{-- ================= KIRI: FORM DATA ================= --}}
                    <div class="w-full lg:w-[65%] space-y-8">
                        
                        {{-- 01. METODE PESANAN --}}
                        <div class="bg-white rounded-[2.5rem] p-6 lg:p-8 brutalist-card relative overflow-hidden">
                            <h2 class="text-2xl font-black italic text-gray-900 uppercase tracking-tighter mb-8 flex items-center gap-4">
                                <span class="bg-brand-red text-white w-10 h-10 rounded-xl flex items-center justify-center text-lg not-italic shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">01</span>
                                Tipe Pesanan
                            </h2>

                            <div class="grid grid-cols-2 gap-6">
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="order_type" value="delivery" id="order_type_delivery" class="peer hidden" checked>
                                    <div class="p-6 rounded-3xl border-4 border-gray-100 bg-slate-50 text-center transition-all peer-checked:border-brand-red peer-checked:bg-white peer-checked:shadow-[6px_6px_0px_0px_rgba(164,31,33,1)] group-hover:-translate-y-1">
                                        <i class="fas fa-motorcycle text-3xl mb-3 text-gray-400 peer-checked:text-brand-red"></i>
                                        <p class="text-xs font-black uppercase tracking-widest text-gray-500 peer-checked:text-gray-900">Delivery</p>
                                    </div>
                                </label>

                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="order_type" value="pickup" id="order_type_pickup" class="peer hidden">
                                    <div class="p-6 rounded-3xl border-4 border-gray-100 bg-slate-50 text-center transition-all peer-checked:border-blue-600 peer-checked:bg-white peer-checked:shadow-[6px_6px_0px_0px_rgba(37,99,235,1)] group-hover:-translate-y-1">
                                        <i class="fas fa-store text-3xl mb-3 text-gray-400 peer-checked:text-blue-600"></i>
                                        <p class="text-xs font-black uppercase tracking-widest text-gray-500 peer-checked:text-gray-900">Pick-Up</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- 02. LOKASI --}}
                        <div class="bg-white rounded-[2.5rem] p-6 lg:p-8 brutalist-card relative">
                            <h2 class="text-2xl font-black italic text-gray-900 uppercase tracking-tighter mb-8 flex items-center gap-4">
                                <span class="bg-brand-red text-white w-10 h-10 rounded-xl flex items-center justify-center text-lg not-italic shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">02</span>
                                Alamat & Outlet
                            </h2>

                            {{-- Custom Dropdown Outlet --}}
                            <div class="mb-8" id="branch-selector-container">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 block ml-1">Pilih Outlet Pizza Boxx</label>
                                <select name="location_id" id="location_id" class="hidden">
                                    <option value="">-- PILIH CABANG --</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" data-address="{{ $location->address }}" data-map-url="{{ $location->maps_url }}">
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <div id="branch-trigger" class="w-full bg-slate-50 border-4 border-gray-900 p-5 rounded-2xl cursor-pointer flex items-center justify-between transition-all hover:bg-brand-kraft/30">
                                    <div class="flex items-center gap-5">
                                        <div class="w-12 h-12 rounded-xl bg-white border-2 border-gray-900 text-brand-red flex items-center justify-center text-xl shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                                            <i class="fas fa-pizza-slice"></i>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black uppercase text-gray-400 leading-none mb-1">Dibuat di:</p>
                                            <h4 id="selected-branch-name" class="text-base font-black text-gray-900 uppercase italic">Klik untuk pilih Cabang...</h4>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-down text-gray-900"></i>
                                </div>

                                <div id="branch-options" class="hidden absolute left-0 right-0 mx-6 lg:mx-8 bg-white border-4 border-gray-900 rounded-[2rem] shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] mt-2 z-50 overflow-hidden max-h-72 overflow-y-auto custom-scrollbar">
                                    @foreach($locations as $location)
                                        <div onclick="selectBranchCustom('{{ $location->id }}', '{{ $location->name }}', '{{ $location->address }}')" 
                                             class="p-5 cursor-pointer hover:bg-brand-kraft border-b-2 border-gray-100 last:border-0 transition-all flex gap-4 items-center group">
                                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center group-hover:bg-white border-2 border-transparent group-hover:border-gray-900">
                                                <i class="fas fa-map-pin text-gray-400 group-hover:text-brand-red"></i>
                                            </div>
                                            <div>
                                                <h5 class="text-sm font-black text-gray-900 uppercase italic">{{ $location->name }}</h5>
                                                <p class="text-[10px] text-gray-500 font-bold mt-0.5">{{ $location->address }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Alamat Customer --}}
                            <div id="address_section" class="space-y-4">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 block ml-1">Kirim Ke Alamat Kamu</label>
                                <div class="border-4 border-gray-900 rounded-[2rem] p-6 relative bg-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <span id="display_label" class="bg-gray-900 text-white text-[10px] font-black uppercase px-3 py-1.5 rounded-lg tracking-widest">{{ $primaryAddress ? $primaryAddress->label : '?' }}</span>
                                            <p id="display_address" class="text-base font-bold text-gray-800 mt-4 leading-relaxed">
                                                {{ $primaryAddress ? $primaryAddress->address : 'Klik tombol ganti untuk pilih alamat.' }}
                                            </p>
                                            <p id="display_phone" class="text-sm font-black text-brand-red mt-2 italic tracking-tight">{{ $primaryAddress ? $primaryAddress->phone : '' }}</p>
                                        </div>
                                        <button type="button" onclick="openAddressBook()" class="bg-white border-4 border-gray-900 text-gray-900 font-black px-5 py-2.5 rounded-xl uppercase text-[11px] tracking-widest hover:bg-gray-900 hover:text-white transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none active:translate-x-1 active:translate-y-1">
                                            GANTI
                                        </button>
                                    </div>
                                    <input type="hidden" name="delivery_address" id="inp_address" value="{{ $primaryAddress ? $primaryAddress->address : '' }}">
                                    <input type="hidden" name="latitude" id="inp_lat" value="{{ $primaryAddress ? $primaryAddress->latitude : '' }}">
                                    <input type="hidden" name="longitude" id="inp_lng" value="{{ $primaryAddress ? $primaryAddress->longitude : '' }}">
                                </div>

                                <div class="mt-4">
                                    <input type="text" name="delivery_notes" id="inp_notes" placeholder="Patokan / Catatan (Contoh: Pagar Merah)" 
                                           class="w-full bg-slate-50 border-2 border-gray-200 focus:border-gray-900 focus:bg-white rounded-2xl py-4 px-6 text-sm font-bold text-gray-900 placeholder-gray-400 transition-all outline-none brutalist-input">
                                </div>
                            </div>

                            {{-- Pickup Info --}}
                            <div id="pickup_section" class="hidden">
                                <div class="bg-brand-kraft/20 border-4 border-dashed border-gray-900 rounded-[2rem] p-8 text-center">
                                    <i class="fas fa-store text-4xl text-gray-900 mb-4"></i>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Kamu akan ambil pesanan di:</p>
                                    <h4 id="pickup_display" class="text-2xl font-black text-gray-900 italic uppercase leading-none">-</h4>
                                    <p id="pickup_address_display" class="text-sm font-bold text-gray-500 mt-2 mb-6">-</p>
                                    <a id="pickup_map_link" href="#" target="_blank" class="inline-flex items-center gap-3 bg-white border-4 border-gray-900 px-6 py-3 rounded-2xl font-black uppercase text-xs hover:bg-gray-900 hover:text-white transition-all shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:shadow-none">
                                        <i class="fas fa-location-arrow"></i> LIHAT PETA
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- 03. PEMBAYARAN --}}
                        <div class="bg-white rounded-[2.5rem] p-6 lg:p-8 brutalist-card">
                            <h2 class="text-2xl font-black italic text-gray-900 uppercase tracking-tighter mb-8 flex items-center gap-4">
                                <span class="bg-brand-red text-white w-10 h-10 rounded-xl flex items-center justify-center text-lg not-italic shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">03</span>
                                Pembayaran
                            </h2>

                            <div id="method_cod" class="space-y-4">
                                <label class="relative block cursor-pointer group">
                                    <input type="radio" name="payment_method" value="cash_on_delivery" class="peer sr-only" checked>
                                    <div class="p-6 rounded-[2rem] border-4 border-gray-100 bg-white transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:shadow-[6px_6px_0px_0px_rgba(16,185,129,1)] flex items-center gap-5">
                                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl border-2 border-emerald-200">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-black text-gray-900 uppercase italic">Cash on Delivery (COD)</h4>
                                            <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Bayar tunai ke kurir saat sampai</p>
                                        </div>
                                        <div class="ml-auto w-8 h-8 rounded-full border-4 border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 flex items-center justify-center transition-all">
                                            <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div id="method_pickup" class="hidden">
                                <label class="relative block cursor-pointer group">
                                    <input type="radio" name="payment_method" value="cash_on_pickup" class="peer sr-only">
                                    <div class="p-6 rounded-[2rem] border-4 border-gray-100 bg-white transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-[6px_6px_0px_0px_rgba(59,130,246,1)] flex items-center gap-5">
                                        <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl border-2 border-blue-200">
                                            <i class="fas fa-wallet"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-black text-gray-900 uppercase italic">Bayar di Kasir</h4>
                                            <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Cash / QRIS saat ambil di outlet</p>
                                        </div>
                                        <div class="ml-auto w-8 h-8 rounded-full border-4 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center transition-all">
                                            <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- ================= KANAN: SUMMARY ================= --}}
                    <div class="w-full lg:w-[35%] lg:sticky lg:top-32">
                        <div class="bg-gray-900 rounded-[3rem] p-8 border-4 border-gray-900 shadow-[12px_12px_0px_0px_rgba(0,0,0,0.15)] relative overflow-hidden">
                            <div class="absolute inset-0 bg-pizza-pattern opacity-[0.05]"></div>
                            
                            <div class="relative z-10">
                                <h3 class="text-white font-black uppercase italic tracking-tighter text-2xl mb-8 border-b-2 border-gray-800 pb-6 flex justify-between items-center">
                                    RINGKASAN
                                    <span class="text-[10px] bg-brand-red px-3 py-1 rounded-full not-italic tracking-widest">{{ count($cart) }} Items</span>
                                </h3>

                                {{-- Cart Items List --}}
                                <div class="space-y-4 mb-8 max-h-56 overflow-y-auto pr-3 custom-scrollbar">
                                    @foreach($cart as $item)
                                        <div class="flex justify-between items-start gap-4 group">
                                            <div class="flex gap-4">
                                                <div class="w-8 h-8 rounded-lg bg-gray-800 text-white font-black text-[11px] flex items-center justify-center border-2 border-gray-700 group-hover:bg-brand-red transition-colors shrink-0">
                                                    {{ $item['quantity'] }}x
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-black text-white uppercase italic leading-none group-hover:text-brand-red transition-colors">{{ $item['name'] }}</h4>
                                                    <p class="text-[9px] text-gray-500 font-black mt-1 uppercase tracking-widest">
                                                        @if(isset($item['size_option']['name'])) {{ $item['size_option']['name'] }} @endif
                                                        @if(isset($item['crust_option']['name'])) • {{ $item['crust_option']['name'] }} @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <p class="text-sm font-black text-gray-300 italic shrink-0">
                                                {{ number_format($item['total_price'] / 1000, 0) }}k
                                            </p>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Promo Section --}}
                                <div class="flex gap-3 mb-8">
                                    <input type="text" id="promo_code" placeholder="KODE PROMO" class="w-full bg-gray-800 border-2 border-gray-700 px-5 py-3 rounded-xl text-xs font-black uppercase text-white placeholder-gray-600 focus:border-brand-red focus:ring-0 outline-none transition-all">
                                    <button type="button" id="btn_promo" class="bg-brand-kraft text-gray-900 px-6 rounded-xl text-[10px] font-black uppercase hover:bg-white transition-all shadow-[3px_3px_0px_0px_rgba(255,255,255,0.2)] active:translate-y-0.5 active:shadow-none">CEK</button>
                                </div>

                                {{-- Final Pricing --}}
                                <div class="space-y-3 mb-10 bg-black/30 p-6 rounded-[2rem] border-2 border-gray-800">
                                    <div class="flex justify-between text-xs font-black text-gray-500 uppercase tracking-widest">
                                        <span>Subtotal</span>
                                        <span class="text-gray-200 italic">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs font-black text-gray-500 uppercase tracking-widest" id="row_fee">
                                        <span>Ongkos Kirim</span>
                                        <span id="txt_fee" class="text-white italic">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between text-xs font-black text-emerald-400 uppercase tracking-widest hidden" id="row_discount">
                                        <span>Potongan Diskon</span>
                                        <span id="txt_discount" class="italic">- Rp 0</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-4 border-t-2 border-gray-800 mt-4">
                                        <span class="font-black text-white uppercase italic text-lg tracking-tighter">TOTAL BAYAR</span>
                                        <span class="font-black text-brand-red text-2xl italic tracking-tighter drop-shadow-sm" id="txt_total">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <input type="hidden" name="subtotal_amount" value="{{ $cartTotal }}">
                                <input type="hidden" name="delivery_fee" id="inp_fee" value="0">
                                <input type="hidden" name="discount_amount" id="inp_discount" value="0">
                                <input type="hidden" name="total_amount" id="inp_total" value="{{ $cartTotal }}">
                                <input type="hidden" name="customer_name" value="{{ $user->name }}">
                                <input type="hidden" name="customer_email" value="{{ $user->email }}">
                                <input type="hidden" name="customer_phone" value="{{ $primaryAddress->phone ?? $user->phone_number }}">

                                {{-- Confirm Button --}}
                                <button type="submit" id="btn_submit" class="group w-full bg-brand-red text-white font-black py-5 px-8 rounded-2xl uppercase tracking-[0.2em] text-sm shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] border-2 border-transparent hover:bg-white hover:text-gray-900 hover:border-gray-900 hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all duration-300 flex items-center justify-center gap-4">
                                    <span>PESAN SEKARANG</span>
                                    <i class="fas fa-fire group-hover:scale-125 transition-transform"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    {{-- MODAL ADDRESS --}}
    <div id="modalAddressBook" class="fixed inset-0 z-[100] hidden bg-gray-900/80 backdrop-blur-md p-4 flex items-center justify-center">
        <div class="bg-white w-full max-w-xl rounded-[3rem] brutalist-card flex flex-col max-h-[85vh] overflow-hidden">
            <div class="p-8 border-b-4 border-gray-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <h3 class="text-2xl font-black uppercase italic text-gray-900 tracking-tighter">ALAMAT TERSIMPAN.</h3>
                <button onclick="document.getElementById('modalAddressBook').classList.add('hidden')" class="w-12 h-12 rounded-xl bg-slate-50 border-2 border-gray-900 hover:bg-brand-red hover:text-white transition-all flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-4 bg-slate-50 flex-grow">
                @forelse($addresses as $addr)
                    <div onclick="selectAddress({{ json_encode($addr) }})" class="group cursor-pointer p-6 rounded-[2rem] border-4 border-transparent bg-white shadow-sm hover:border-gray-900 hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] transition-all relative">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="bg-gray-900 text-white text-[9px] font-black uppercase px-3 py-1.5 rounded-lg tracking-widest">{{ $addr->label }}</span>
                                <p class="text-base font-bold text-gray-900 mt-4 leading-tight">{{ $addr->address }}</p>
                                <p class="text-[10px] text-gray-400 font-black mt-2 uppercase tracking-widest italic">{{ $addr->city }} • {{ $addr->phone }}</p>
                            </div>
                            <div class="w-8 h-8 rounded-full border-4 border-gray-200 flex items-center justify-center group-hover:border-emerald-500 group-hover:bg-emerald-500 transition-all">
                                <i class="fas fa-check text-white text-[10px] opacity-0 group-hover:opacity-100"></i>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20">
                        <i class="fas fa-map-marked-alt text-5xl text-gray-200 mb-4"></i>
                        <p class="text-gray-400 font-black text-xs uppercase tracking-widest">Belum ada alamat.</p>
                    </div>
                @endforelse
            </div>
            <div class="p-6 border-t-4 border-gray-100 bg-white">
                <button type="button" onclick="openCreateAddress()" class="block w-full border-4 border-dashed border-gray-300 text-gray-400 font-black py-5 rounded-[1.5rem] text-center uppercase text-xs tracking-[0.2em] hover:border-gray-900 hover:text-gray-900 hover:bg-brand-kraft/20 transition-all">
                    + TAMBAH ALAMAT BARU
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL FORM TAMBAH ALAMAT BARU --}}
    <div id="modalCreateAddress" class="fixed inset-0 z-[110] hidden bg-gray-900/80 backdrop-blur-sm p-4 flex items-center justify-center">
        <div class="bg-white w-full max-w-5xl rounded-[3rem] brutalist-card flex flex-col max-h-[90vh] overflow-hidden border-4 border-gray-900 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]">
            <div class="p-6 border-b-4 border-gray-100 flex justify-between items-center bg-white">
                <h3 class="text-2xl font-black uppercase italic text-gray-900 tracking-tighter">ALAMAT BARU.</h3>
                <button type="button" onclick="closeCreateAddress()" class="w-12 h-12 rounded-xl bg-slate-50 border-2 border-gray-900 hover:bg-brand-red hover:text-white transition-all flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="formCreateAddress" class="overflow-y-auto custom-scrollbar p-6 lg:p-8 bg-slate-50">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- KOLOM KIRI: INPUT DATA --}}
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Nama Penerima</label>
                            <input type="text" name="receiver_name" value="{{ Auth::user()->name }}" required class="w-full bg-white border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 outline-none font-bold text-sm brutalist-input mt-1">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Label</label>
                                <input type="text" name="label" required placeholder="Rumah/Kantor" class="w-full bg-white border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 outline-none font-bold text-sm brutalist-input mt-1">
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">No. Telepon</label>
                                <input type="tel" name="phone" value="{{ Auth::user()->phone_number }}" required class="w-full bg-white border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 outline-none font-bold text-sm brutalist-input mt-1">
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Alamat Peta (Otomatis)</label>
                            <textarea name="map_address" id="modal-map-address" rows="2" class="w-full bg-gray-100 border-2 border-gray-200 p-4 rounded-xl font-bold text-xs text-gray-500 italic cursor-not-allowed mt-1" readonly placeholder="Titik di peta..."></textarea>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Detail Alamat</label>
                            <textarea name="detail_address" required rows="2" placeholder="Contoh: Jl. Pahlawan, No.1, Unit Melati, Lobby Utara, ..." class="w-full bg-white border-2 border-gray-200 p-4 rounded-xl focus:border-gray-900 outline-none font-bold text-sm brutalist-input mt-1 resize-none"></textarea>
                        </div>

                        <input type="hidden" name="latitude" id="modal-lat">
                        <input type="hidden" name="longitude" id="modal-lng">
                        <input type="hidden" name="city" id="modal-city">
                    </div>

                    {{-- KOLOM KANAN: PETA --}}
                    <div class="flex flex-col h-full min-h-[300px]">
                        <div class="flex gap-2 mb-3">
                            <input type="text" id="modal-map-search" class="w-full bg-white border-2 border-gray-200 p-3 rounded-xl focus:border-gray-900 outline-none font-bold text-xs" placeholder="Cari lokasi/jalan...">
                            <button type="button" onclick="searchAddressModal()" class="bg-gray-900 text-white px-5 rounded-xl font-black text-[10px] uppercase shadow-sm hover:bg-brand-red">CARI</button>
                        </div>
                        
                        <div class="flex-grow relative border-4 border-gray-900 rounded-[2rem] overflow-hidden shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                            <div id="map-modal" class="h-full w-full"></div>
                            <button type="button" onclick="getCurrentLocationModal(event)" class="absolute top-3 right-3 z-[999] bg-white text-brand-red p-3 rounded-xl border-2 border-gray-900 shadow-md hover:bg-brand-red hover:text-white transition-all">
                                <i class="fas fa-location-arrow"></i>
                            </button>
                        </div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase italic mt-3 text-center"><i class="fas fa-info-circle mr-1"></i> Geser pin ke titik jemput yang pas</p>
                    </div>
                </div>
            </form>

            <div class="p-6 border-t-4 border-gray-100 bg-white">
                <button type="button" id="btnSaveAddress" onclick="saveNewAddress()" class="w-full bg-gray-900 text-white font-black py-5 rounded-[1.5rem] uppercase text-xs tracking-[0.2em] shadow-[4px_4px_0px_0px_rgba(220,38,38,1)] hover:bg-brand-red transition-all flex items-center justify-center gap-3">
                    <span>SIMPAN & GUNAKAN</span>
                    <i class="fas fa-save"></i>
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Variabel global untuk Peta Modal (agar bisa diakses antar fungsi)
        let modalMap = null;
        let modalMarker = null;

        document.addEventListener('DOMContentLoaded', function () {
            const subtotal = parseFloat("{{ $cartTotal }}") || 0;
            let currentFee = 0;
            let currentDiscount = 0;
            let isCalculating = false;

            const el = {
                form: document.getElementById('checkoutForm'),
                realSelect: document.getElementById('location_id'),
                branchTrigger: document.getElementById('branch-trigger'),
                branchOptions: document.getElementById('branch-options'),
                branchNameDisplay: document.getElementById('selected-branch-name'),
                triggerContainer: document.getElementById('branch-selector-container'),
                btn: document.getElementById('btn_submit'),
                orderTypeDelivery: document.getElementById('order_type_delivery'),
                orderTypePickup: document.getElementById('order_type_pickup'),
                methodCod: document.getElementById('method_cod'),
                methodPickup: document.getElementById('method_pickup'),
                total: document.getElementById('txt_total'),
                totalMobile: document.getElementById('txt_total_mobile'),
                fee: document.getElementById('txt_fee'),
                rowFee: document.getElementById('row_fee'),
                rowDisc: document.getElementById('row_discount'),
                txtDisc: document.getElementById('txt_discount'),
                inpFee: document.getElementById('inp_fee'),
                inpDiscount: document.getElementById('inp_discount'),
                inpTotal: document.getElementById('inp_total'),
                secAddr: document.getElementById('address_section'),
                lblDisplay: document.getElementById('display_label'),
                addrDisplay: document.getElementById('display_address'),
                phoneDisplay: document.getElementById('display_phone'),
                inpLat: document.getElementById('inp_lat'),
                inpLng: document.getElementById('inp_lng'),
                inpAddr: document.getElementById('inp_address'),
                secPickup: document.getElementById('pickup_section'),
                displayPickupName: document.getElementById('pickup_display'),
                displayPickupAddr: document.getElementById('pickup_address_display'),
                btnPickupMap: document.getElementById('pickup_map_link'),
                promoCode: document.getElementById('promo_code'),
                btnPromo: document.getElementById('btn_promo'),
                // Tambahan untuk Modal Alamat Baru
                modalMapAddr: document.getElementById('modal-map-address'),
                modalLat: document.getElementById('modal-lat'),
                modalLng: document.getElementById('modal-lng'),
                modalCity: document.getElementById('modal-city'),
                formNewAddr: document.getElementById('formCreateAddress')
            };

            const PizzaAlert = Swal.mixin({
                customClass: {
                    confirmButton: 'bg-gray-900 text-white font-black uppercase italic px-8 py-4 rounded-2xl mx-2 hover:bg-brand-red transition-all text-[10px] tracking-[0.2em] shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]',
                    popup: 'rounded-[3rem] border-4 border-gray-900 shadow-2xl p-8',
                    title: 'font-black uppercase italic text-gray-900 tracking-tighter text-2xl mb-4',
                    htmlContainer: 'font-bold text-gray-500 text-[11px] uppercase tracking-widest leading-relaxed'
                },
                buttonsStyling: false
            });

            // ==========================================
            // 1. LOGIKA NAVIGASI MODAL & PETA
            // ==========================================
            window.openCreateAddress = function() {
                document.getElementById('modalAddressBook').classList.add('hidden');
                document.getElementById('modalCreateAddress').classList.remove('hidden');
                
                // Inisialisasi peta dengan delay agar kontainer siap
                setTimeout(() => {
                    initModalMap();
                    if(modalMap) modalMap.invalidateSize(); 
                }, 300);
            };

            window.closeCreateAddress = function() {
                document.getElementById('modalCreateAddress').classList.add('hidden');
                document.getElementById('modalAddressBook').classList.remove('hidden');
            };

            function initModalMap() {
                if (!modalMap) {
                    modalMap = L.map('map-modal', { zoomControl: false }).setView([-6.200000, 106.816666], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(modalMap);
                    L.control.zoom({ position: 'bottomright' }).addTo(modalMap);
                    
                    modalMarker = L.marker([-6.200000, 106.816666], { draggable: true }).addTo(modalMap);
                    
                    const updatePos = (lat, lng) => {
                        el.modalLat.value = lat;
                        el.modalLng.value = lng;
                        reverseGeocodeModal(lat, lng);
                    };

                    modalMarker.on('dragend', (e) => { 
                        const pos = e.target.getLatLng(); 
                        updatePos(pos.lat, pos.lng); 
                    });
                    
                    modalMap.on('click', (e) => { 
                        modalMarker.setLatLng(e.latlng); 
                        updatePos(e.latlng.lat, e.latlng.lng); 
                    });
                }
            }

            async function reverseGeocodeModal(lat, lng) {
                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                    const data = await res.json();
                    if(data) {
                        el.modalMapAddr.value = data.display_name;
                        el.modalCity.value = data.address.city || data.address.town || data.address.village || '';
                    }
                } catch (e) { console.error(e); }
            }

            window.searchAddressModal = async function() {
                const q = document.getElementById('modal-map-search').value;
                if(!q) return;
                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${q}`);
                    const data = await res.json();
                    if(data.length > 0) {
                        const pos = [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                        modalMap.setView(pos, 16); 
                        modalMarker.setLatLng(pos);
                        el.modalLat.value = pos[0];
                        el.modalLng.value = pos[1];
                        el.modalMapAddr.value = data[0].display_name;
                    }
                } catch (e) { console.error(e); }
            };

            window.getCurrentLocationModal = function(event) {
                if(!navigator.geolocation) return PizzaAlert.fire('Error', 'GPS tidak didukung', 'error');
                navigator.geolocation.getCurrentPosition((pos) => {
                    const lat = pos.coords.latitude; 
                    const lng = pos.coords.longitude;
                    modalMap.setView([lat, lng], 18); 
                    modalMarker.setLatLng([lat, lng]);
                    el.modalLat.value = lat;
                    el.modalLng.value = lng;
                    reverseGeocodeModal(lat, lng);
                });
            };

            // ==========================================
            // 2. SIMPAN ALAMAT VIA AJAX
            // ==========================================
            window.saveNewAddress = function() {
                const form = el.formNewAddr; // Pastikan el.formNewAddr sudah benar
                const btn = document.getElementById('btnSaveAddress');

                // --- PAGAR 1: Paksa Browser Cek Validasi HTML ---
                if (!form.reportValidity()) {
                    return; // Berhenti di sini kalau ada yang kosong (required)
                }

                // --- PAGAR 2: Cek Titik Peta (Karena Peta gak bisa dikasih 'required') ---
                if (!el.modalLat.value || !el.modalLng.value) {
                    return PizzaAlert.fire('TITIK PETA KOSONG!', 'Klik di peta dulu buat nentuin lokasi pengantaran ya.', 'warning');
                }

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner animate-spin"></i> MEMPROSES...';

                fetch("{{ route('user.address.store') }}", { 
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: new FormData(form)
                })
                .then(res => res.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerHTML = '<span>SIMPAN & GUNAKAN</span><i class="fas fa-save"></i>';

                    if(data.success) {
                        // --- FIX UNDEFINED: Gunakan Operator OR (||) sebagai Fallback ---
                        // Kita ambil data.address.detail_address kalau ada, kalau nggak ada pakai data.address.address
                        const displayAddress = data.address.detail_address || data.address.address || "Alamat baru";

                        el.lblDisplay.innerText = data.address.label;
                        el.addrDisplay.innerText = displayAddress; 
                        el.phoneDisplay.innerText = data.address.phone;
                        
                        el.inpAddr.value = data.address.address; 
                        el.inpLat.value = data.address.latitude;
                        el.inpLng.value = data.address.longitude;

                        document.getElementById('modalCreateAddress').classList.add('hidden');
                        calculateTotal();

                        PizzaAlert.fire({ icon: 'success', title: 'BERHASIL!', text: 'Alamat disimpan.', timer: 1500, showConfirmButton: false });
                    } else {
                        PizzaAlert.fire('GAGAL!', data.message || 'Cek kembali data kamu.', 'error');
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    PizzaAlert.fire('ERROR', 'Koneksi bermasalah.', 'error');
                });
            };

            // ==========================================
            // 3. LOGIKA CHECKOUT LAMA (ONGKIR, PROMO, DLL)
            // ==========================================
            
            // --- Branch Selector ---
            if(el.branchTrigger) {
                el.branchTrigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    el.branchOptions.classList.toggle('hidden');
                    el.branchTrigger.classList.toggle('border-brand-red');
                });
            }
            document.addEventListener('click', (e) => {
                if (el.triggerContainer && !el.triggerContainer.contains(e.target)) {
                    el.branchOptions.classList.add('hidden');
                    el.branchTrigger.classList.remove('border-brand-red');
                }
            });
            window.selectBranchCustom = function(id, name, address) {
                el.branchNameDisplay.innerText = name;
                el.branchOptions.classList.add('hidden');
                el.realSelect.value = id;
                el.realSelect.dispatchEvent(new Event('change'));
                el.branchTrigger.classList.add('bg-emerald-50', 'border-emerald-500');
                setTimeout(() => el.branchTrigger.classList.remove('bg-emerald-50', 'border-emerald-500'), 1000);
            };

            // --- Core Calculate Logic ---
            el.realSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                if(!selected.value) return;

                // Ambil data dari attribute option
                const name = selected.text.trim();
                const address = selected.getAttribute('data-address') || '';
                let mapUrl = selected.getAttribute('data-map-url'); // Ambil URL dari DB jika ada

                // Update Tampilan Text
                el.displayPickupName.innerText = name;
                el.displayPickupAddr.innerText = address;

                // Jika di database link map kosong, kita buatkan link otomatis berdasarkan Nama & Alamat
                if (!mapUrl || mapUrl === 'null' || mapUrl === '') {
                    mapUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(name + ' ' + address)}`;
                }
                
                // Pasang link ke tombol
                if (el.btnPickupMap) {
                    el.btnPickupMap.href = mapUrl;
                }

                calculateTotal();
            });

            window.openAddressBook = function() { 
                document.getElementById('modalAddressBook').classList.remove('hidden'); 
            };

            window.selectAddress = function(addr) {
                el.lblDisplay.innerText = addr.label;
                el.addrDisplay.innerText = addr.address;
                el.phoneDisplay.innerText = addr.phone;
                el.inpLat.value = addr.latitude;
                el.inpLng.value = addr.longitude;
                el.inpAddr.value = addr.address;
                document.getElementById('modalAddressBook').classList.add('hidden');
                calculateTotal(); 
            };

            function calculateTotal() {
                if (el.orderTypePickup.checked) {
                    currentFee = 0;
                    updateSummary();
                    return;
                }
                const locId = el.realSelect.value;
                const lat = el.inpLat.value;
                const lng = el.inpLng.value;
                if (!locId || !lat || !lng) return;

                isCalculating = true;
                el.btn.disabled = true;
                el.btn.innerHTML = '<i class="fas fa-spinner animate-spin mr-2"></i> MENGHITUNG JARAK...';

                fetch('/api/check-delivery', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ location_id: locId, latitude: lat, longitude: lng })
                })
                .then(res => res.json())
                .then(data => {
                    isCalculating = false;
                    if (data.allowed) {
                        currentFee = parseFloat(data.delivery_fee) || 0;
                        el.btn.disabled = false;
                        el.btn.innerHTML = '<span>PESAN SEKARANG</span><i class="fas fa-fire ml-2"></i>';
                    } else {
                        currentFee = 0;
                        PizzaAlert.fire({
                            icon: 'error', title: 'WADUH, KEJAUHAN!', text: data.message, confirmButtonText: 'PAKAI PICK-UP AJA'
                        }).then((res) => { if(res.isConfirmed) el.orderTypePickup.click(); });
                        el.btn.innerHTML = 'JARAK TIDAK TERJANGKAU';
                    }
                    updateSummary();
                });
            }

            // --- Switch Order Type ---
            const toggleOrderType = () => {
                if(el.orderTypeDelivery.checked) {
                    el.secAddr.classList.remove('hidden');
                    el.secPickup.classList.add('hidden');
                    el.rowFee.classList.remove('hidden');
                    el.methodCod.classList.remove('hidden');
                    el.methodPickup.classList.add('hidden');
                    calculateTotal();
                } else {
                    el.secAddr.classList.add('hidden');
                    el.secPickup.classList.remove('hidden');
                    el.rowFee.classList.add('hidden');
                    el.methodCod.classList.add('hidden');
                    el.methodPickup.classList.remove('hidden');
                    currentFee = 0;
                    updateSummary();
                    el.btn.disabled = false;
                    el.btn.innerHTML = '<span>PESAN SEKARANG</span><i class="fas fa-fire ml-2"></i>';
                }
            };
            el.orderTypeDelivery.addEventListener('change', toggleOrderType);
            el.orderTypePickup.addEventListener('change', toggleOrderType);

            // --- Promo Logic ---
            el.btnPromo.addEventListener('click', function() {
                const code = el.promoCode.value;
                if(!code) return PizzaAlert.fire('Eits!', 'Masukkan kode promo dulu.', 'warning');
                el.btnPromo.innerHTML = '<i class="fas fa-spinner animate-spin"></i>';
                fetch('/api/validate-promo', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ promo_code: code, subtotal: subtotal })
                })
                .then(res => res.json())
                .then(data => {
                    el.btnPromo.innerHTML = 'CEK';
                    if(data.success) {
                        currentDiscount = parseFloat(data.discount_amount) || 0;
                        el.rowDisc.classList.remove('hidden');
                        PizzaAlert.fire({ icon: 'success', title: 'Hore!', text: data.message, timer: 1500, showConfirmButton: false });
                    } else {
                        currentDiscount = 0;
                        el.rowDisc.classList.add('hidden');
                        PizzaAlert.fire({ icon: 'error', title: 'Gagal', text: data.message });
                    }
                    updateSummary();
                });
            });

            // --- Submit Validation ---
            el.form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (!el.realSelect.value) {
                    PizzaAlert.fire('PILIH OUTLET!', 'Pilih lokasi outlet dulu ya.', 'warning');
                    return;
                }
                if (el.orderTypeDelivery.checked && (!el.inpAddr.value || !el.inpLat.value)) {
                    PizzaAlert.fire('ALAMAT KOSONG!', 'Isi alamat dulu biar kurir nggak nyasar.', 'warning');
                    return;
                }
                if (isCalculating) return;
                el.btn.disabled = true;
                el.btn.innerHTML = '<i class="fas fa-cookie-bite animate-spin mr-2"></i> MEMPROSES...';
                this.submit();
            });

            function updateSummary() {
                const total = (subtotal + currentFee) - currentDiscount;
                const fmt = (num) => 'Rp ' + Math.round(num).toLocaleString('id-ID');
                el.fee.innerText = fmt(currentFee);
                el.txtDisc.innerText = '- ' + fmt(currentDiscount);
                el.total.innerText = fmt(total);
                if(el.totalMobile) el.totalMobile.innerText = fmt(total);
                el.inpFee.value = currentFee;
                el.inpDiscount.value = currentDiscount;
                el.inpTotal.value = total;
            }

            // --- 7. INITIAL TRIGGER ---
            if(el.inpLat.value && el.realSelect.value) {
                el.realSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>