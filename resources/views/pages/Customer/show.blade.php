@extends('layouts.customer')

@section('title', 'Lacak Pesanan #' . $order->order_code)

@section('content')
{{-- Tambah pt-32 agar tidak tertutup Navbar, relative z-10 agar di atas shadow navbar --}}
<div class="bg-slate-50 min-h-screen pt-32 md:pt-40 pb-24 relative z-10">
    <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
        
        {{-- HEADER: Responsif (Mobile: Center, Desktop: Spread) --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            {{-- Tombol Kembali --}}
            <div class="flex justify-center md:justify-start">
                <a href="{{ route('user.dashboard') }}" class="group inline-flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-brand-red transition-all">
                    <div class="w-10 h-10 rounded-2xl bg-white border-2 border-gray-900 flex items-center justify-center group-hover:bg-brand-red group-hover:text-white transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] group-hover:shadow-none group-hover:translate-x-[2px] group-hover:translate-y-[2px]">
                        <i class="fas fa-arrow-left"></i>
                    </div>
                    <span class="hidden sm:inline">Kembali ke Dashboard</span>
                    <span class="sm:hidden uppercase">Kembali</span>
                </a>
            </div>
            
            {{-- Nomor Order --}}
            <div class="text-center md:text-right">
                <p class="text-[9px] font-black uppercase tracking-[0.4em] text-gray-400 mb-1">Status Tracking</p>
                <h1 class="text-3xl md:text-4xl font-black italic uppercase tracking-tighter text-gray-900 leading-none">
                    Order <span class="text-brand-red">#{{ $order->order_code }}</span>
                </h1>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            {{-- === KOLOM KIRI (2/3): STATUS, TIMELINE, ITEM === --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- 1. STATUS BANNER (Dynamic Color) --}}
                @php
                    $s = strtolower($order->status);

                    $statusConfig = [
                        'pending'            => ['bg' => 'bg-yellow-100', 'border' => 'border-yellow-900', 'text' => 'text-yellow-900', 'icon' => 'fa-clock', 'label' => 'Menunggu Konfirmasi'],
                        'accepted'           => ['bg' => 'bg-blue-50', 'border' => 'border-blue-900', 'text' => 'text-blue-900', 'icon' => 'fa-check', 'label' => 'Pesanan Diterima'],
                        'preparing'          => ['bg' => 'bg-orange-100', 'border' => 'border-orange-900', 'text' => 'text-orange-900', 'icon' => 'fa-fire', 'label' => 'Sedang Dimasak'],
                        'ready_for_delivery' => ['bg' => 'bg-emerald-100', 'border' => 'border-emerald-900', 'text' => 'text-emerald-900', 'icon' => 'fa-motorcycle', 'label' => 'Siap Diantar'],
                        'ready_for_pickup'   => ['bg' => 'bg-emerald-100', 'border' => 'border-emerald-900', 'text' => 'text-emerald-900', 'icon' => 'fa-store', 'label' => 'Siap Diambil'],
                        'on_delivery'        => ['bg' => 'bg-blue-100', 'border' => 'border-blue-900', 'text' => 'text-blue-900', 'icon' => 'fa-shipping-fast', 'label' => 'Kurir Menuju Lokasimu'],
                        'delivered'          => ['bg' => 'bg-green-100', 'border' => 'border-green-900', 'text' => 'text-green-900', 'icon' => 'fa-box-open', 'label' => 'Pesanan Tiba'],
                        'completed'          => ['bg' => 'bg-gray-900', 'border' => 'border-gray-900', 'text' => 'text-white', 'icon' => 'fa-check-circle', 'label' => 'Selesai'],
                        'cancelled'          => ['bg' => 'bg-red-100', 'border' => 'border-red-900', 'text' => 'text-red-900', 'icon' => 'fa-times-circle', 'label' => 'Dibatalkan'],
                    ];

                    $config = $statusConfig[$s] ?? $statusConfig['pending'];
                @endphp

                <div class="{{ $config['bg'] }} rounded-[2.5rem] p-8 border-2 {{ $config['border'] }} relative overflow-hidden shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
                    {{-- Decoration --}}
                    <div class="absolute -right-6 -bottom-6 text-9xl opacity-10 {{ $config['text'] }} transform rotate-12">
                        <i class="fas {{ $config['icon'] }}"></i>
                    </div>

                    <div class="relative z-10">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] {{ $config['text'] }} opacity-70 mb-2">Status Pesanan</p>
                        <h2 class="text-3xl md:text-4xl font-black italic uppercase tracking-tighter {{ $config['text'] }}">
                            {{ $config['label'] }}
                        </h2>
                        <div class="mt-4 flex items-center gap-3 text-xs font-bold {{ $config['text'] }}">
                            <i class="far fa-calendar-alt"></i> {{ $order->created_at->format('d F Y, H:i') }} WIB
                        </div>
                    </div>
                </div>

                {{-- 2. TIMELINE PROGRESS --}}
                <div class="bg-white rounded-[2.5rem] p-8 border-2 border-gray-900 shadow-sm">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-8 border-b-2 border-gray-100 pb-4">Lacak Perjalanan</h3>
                    
                    <div class="relative pl-2">
                        {{-- Garis Background --}}
                        <div class="absolute left-[1.35rem] top-2 bottom-4 w-1 bg-gray-100"></div>

                        @php
                            $steps = [
                                [
                                    'status' => ['accepted', 'preparing', 'ready_for_delivery', 'ready_for_pickup', 'on_delivery', 'delivered', 'completed'], 
                                    'icon' => 'fa-receipt', 
                                    'title' => 'Pesanan Diterima', 
                                    'desc' => 'Kami sudah menerima pesananmu.'
                                ],
                                [
                                    'status' => ['preparing', 'ready_for_delivery', 'ready_for_pickup', 'on_delivery', 'delivered', 'completed'], 
                                    'icon' => 'fa-fire-alt', 
                                    'title' => 'Masuk Dapur', 
                                    'desc' => 'Pizza sedang dipanggang dengan cinta.'
                                ],
                                [
                                    'status' => ['ready_for_delivery', 'ready_for_pickup', 'on_delivery', 'delivered', 'completed'], 
                                    'icon' => $order->order_type == 'delivery' ? 'fa-motorcycle' : 'fa-store', 
                                    'title' => $order->order_type == 'delivery' ? 'Siap Diantar' : 'Siap Diambil', 
                                    'desc' => $order->order_type == 'delivery' ? 'Kurir segera meluncur.' : 'Silakan ambil di outlet terpilih.'
                                ],
                                [
                                    'status' => ['completed'], 
                                    'icon' => 'fa-smile-beam', 
                                    'title' => 'Selesai', 
                                    'desc' => 'Terima kasih sudah memesan!'
                                ],
                            ];
                        @endphp

                        @foreach($steps as $step)
                            @php $isActive = in_array(strtolower($order->status), $step['status']); @endphp
                            <div class="relative flex items-start mb-8 last:mb-0 group">
                                {{-- Icon --}}
                                <div class="w-12 h-12 rounded-xl border-2 flex items-center justify-center z-10 transition-all duration-300 
                                    {{ $isActive ? 'bg-brand-red border-brand-red text-white shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]' : 'bg-white border-gray-200 text-gray-300' }}">
                                    <i class="fas {{ $step['icon'] }} text-sm"></i>
                                </div>
                                {{-- Text --}}
                                <div class="ml-6 pt-1">
                                    <h4 class="text-sm font-black uppercase italic tracking-tight {{ $isActive ? 'text-gray-900' : 'text-gray-300' }}">{{ $step['title'] }}</h4>
                                    <p class="text-[10px] font-bold {{ $isActive ? 'text-gray-500' : 'text-gray-200' }} mt-1 leading-relaxed">{{ $step['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 3. DAFTAR ITEM (Bento Style) --}}
                <div class="bg-white rounded-[2.5rem] p-8 border-2 border-gray-900 shadow-sm">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-6 border-b-2 border-gray-100 pb-4">Menu Dipesan</h3>
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border-2 border-transparent hover:border-gray-200 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center shadow-sm border border-gray-100 overflow-hidden shrink-0">
                                    <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : asset('images/pizza-placeholder.png') }}" class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-gray-900 uppercase italic leading-none mb-1">{{ $item->product_name }}</h4>
                                    <div class="flex flex-wrap gap-1.5">
                                        <span class="text-[9px] font-black text-white bg-brand-red px-1.5 py-0.5 rounded uppercase">{{ $item->quantity }}x</span>
                                        @if(!empty($item->options))
                                            @foreach($item->options as $opt)
                                                <span class="text-[9px] font-bold uppercase text-gray-500 px-1.5 py-0.5 bg-white border border-gray-200 rounded tracking-tighter">{{ $opt['name'] }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm font-black text-gray-900">Rp {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- === KOLOM KANAN (1/3): STICKY INFO === --}}
            <div class="space-y-6 lg:sticky lg:top-24">
                
                {{-- PIN CARD: Tampil untuk Pickup & Delivery selama belum selesai --}}
                @if(!in_array($order->status, ['completed', 'cancelled']))
                <div class="bg-brand-red text-white rounded-[2.5rem] p-8 border-2 border-gray-900 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] text-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-pizza-pattern opacity-10"></div>
                    <div class="relative z-10">
                        <p class="text-[9px] font-black uppercase tracking-[0.3em] opacity-80 mb-2">Verifikasi Pesanan</p>
                        
                        {{-- PIN Utama --}}
                        <div class="bg-white text-brand-red text-4xl font-black font-mono py-3 rounded-xl tracking-widest shadow-inner mb-4">
                            {{ $order->pickup_pin }}
                        </div>
                        
                        {{-- Instruksi Dinamis --}}
                        <p class="text-[10px] font-medium opacity-90 leading-relaxed">
                            <i class="fas fa-info-circle mr-1"></i>
                            @if($order->order_type == 'pickup')
                                Tunjukkan PIN ini ke kasir saat mengambil pesanan.
                            @else
                                Berikan PIN ini ke kurir saat pesanan sampai di tempatmu.
                            @endif
                        </p>
                    </div>
                </div>
                @endif

                {{-- CUSTOMER INFO --}}
                <div class="bg-white rounded-[2.5rem] p-8 border-2 border-gray-900 shadow-sm">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-6 border-b-2 border-gray-100 pb-4">Info Pengiriman</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest mb-1">Penerima</p>
                            <p class="text-sm font-black text-gray-800 uppercase">{{ $order->customer_name }}</p>
                            <p class="text-xs font-bold text-gray-500">{{ $order->customer_phone }}</p>
                        </div>
                        @if($order->order_type == 'delivery')
                        <div>
                            <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest mb-1">Alamat</p>
                            <p class="text-xs font-bold text-gray-600 leading-relaxed">{{ $order->delivery_address }}</p>
                            @if($order->delivery_notes)
                                <p class="text-[10px] text-brand-red mt-1 italic">"{{ $order->delivery_notes }}"</p>
                            @endif
                        </div>
                        @else
                        <div>
                            <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest mb-1">Lokasi Outlet</p>
                            <p class="text-xs font-black text-gray-800 uppercase">{{ $order->location->name }}</p>
                            <p class="text-[10px] font-bold text-gray-500">{{ $order->location->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- BLACK SUMMARY CARD (Sticky Style) --}}
                <div class="bg-gray-900 rounded-[2.5rem] p-8 border-2 border-gray-900 shadow-2xl relative overflow-hidden">
                    <div class="absolute inset-0 bg-pizza-pattern opacity-[0.03]"></div>
                    <div class="relative z-10">
                        <div class="space-y-3 mb-6 border-b border-gray-800 pb-6">
                            <div class="flex justify-between items-center text-gray-400">
                                <span class="text-[10px] font-black uppercase tracking-widest">Subtotal</span>
                                <span class="text-xs font-bold">Rp {{ number_format($order->subtotal_amount, 0, ',', '.') }}</span>
                            </div>
                            @if($order->delivery_fee > 0)
                            <div class="flex justify-between items-center text-gray-400">
                                <span class="text-[10px] font-black uppercase tracking-widest">Ongkir</span>
                                <span class="text-xs font-bold">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            @if($order->discount_amount > 0)
                            <div class="flex justify-between items-center text-green-400">
                                <span class="text-[10px] font-black uppercase tracking-widest">Diskon</span>
                                <span class="text-xs font-bold">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="mb-8 text-center">
                            <p class="text-gray-500 font-black uppercase tracking-widest text-[9px] mb-1">Total Dibayar</p>
                            <p class="text-4xl font-black text-brand-red italic tracking-tighter">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <div class="inline-block mt-2 px-3 py-1 bg-gray-800 rounded-lg">
                                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">
                                    {{ str_replace('_', ' ', $order->payment_method) }}
                                </p>
                            </div>
                        </div>

                        @if($order->status == 'delivered')
                        <form action="{{ route('user.order.complete', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-brand-red hover:bg-white hover:text-gray-900 text-white font-black py-4 rounded-xl uppercase tracking-widest text-[10px] shadow-[3px_3px_0px_0px_rgba(255,255,255,0.2)] hover:shadow-[2px_2px_0px_0px_rgba(255,255,255,1)] hover:translate-x-[1px] hover:translate-y-[1px] transition-all flex items-center justify-center gap-2 border-2 border-transparent">
                                Konfirmasi Diterima <i class="fas fa-check-circle"></i>
                            </button>
                        </form>
                        @endif
                        
                        {{-- Tombol Bantuan WA --}}
                        <a href="https://wa.me/6281218928030?text=Halo%20Admin%2C%20saya%20mau%20tanya%20order%20%23{{ $order->id }}" target="_blank" class="block w-full text-center mt-3 text-gray-500 hover:text-white text-[10px] font-bold uppercase transition-colors">
                            <i class="fab fa-whatsapp mr-1"></i> Hubungi Admin
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- AUTO REFRESH LOGIC (30s) --}}
@if(!in_array($order->status, ['completed', 'cancelled']))
<script>
    setTimeout(function(){
       window.location.reload();
    }, 30000); 
</script>
@endif

@endsection