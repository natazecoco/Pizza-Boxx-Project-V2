@extends('layouts.employee')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-lg"> {{-- max-w-lg agar pas di layar HP --}}
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('pegawai.deliveries.index') }}" class="bg-white p-3 rounded-2xl shadow-sm text-gray-600 hover:text-red-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-xl font-bold text-gray-800">Navigasi Pengantaran</h2>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 mb-6">
        <div class="bg-red-600 p-6 text-white">
            <p class="text-xs opacity-80 uppercase font-bold tracking-widest">Antar Pesanan #{{ $order->id }}</p>
            <h1 class="text-2xl font-black mt-1 uppercase">{{ $order->customer_name }}</h1>
        </div>
        
        <div class="p-6">
            <div class="flex gap-4 mb-8">
                <div class="bg-red-50 p-4 rounded-2xl text-red-600 h-fit">
                    <i class="fas fa-map-marked-alt text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Alamat Pengiriman:</p>
                    <p class="text-gray-700 font-bold leading-relaxed mt-1">
                        {{ $order->delivery_address ?? 'Alamat belum diisi' }}
                    </p>
                </div>
            </div>

            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($order->delivery_address) }}" 
               target="_blank"
               class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-center flex items-center justify-center gap-3 shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all mb-4">
                <i class="fas fa-location-arrow"></i> BUKA GOOGLE MAPS
            </a>

            @php
                $phone = $order->customer_phone;
                if(str_starts_with($phone, '0')) {
                    $phone = '62' . substr($phone, 1);
                }
            @endphp
            <a href="https://wa.me/{{ $phone }}?text=Halo%20Kak%20{{ urlencode($order->customer_name) }},%20kurir%20Pizza%20Boxx%20sedang%20menuju%20lokasi%20ya!" 
               target="_blank"
               class="w-full bg-green-500 text-white py-4 rounded-2xl font-black text-center flex items-center justify-center gap-3 shadow-lg shadow-green-100 hover:bg-green-600 transition-all">
                <i class="fab fa-whatsapp text-xl"></i> HUBUNGI PELANGGAN
            </a>
        </div>
    </div>

    <div class="bg-gray-50 rounded-3xl p-6 border border-gray-200 mb-24">
        <h3 class="text-xs font-bold text-gray-400 uppercase mb-4 tracking-widest">Cek Barang Bawaan:</h3>
        <ul class="space-y-3">
            @foreach($order->orderItems as $item)
            <li class="flex justify-between items-center bg-white p-3 rounded-xl border border-gray-100">
                <span class="text-sm font-bold text-gray-700">
                    <span class="text-red-600">{{ $item->quantity }}x</span> {{ $item->product_name }}
                </span>
                <i class="far fa-circle text-gray-300"></i>
            </li>
            @endforeach
        </ul>
        <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
            <span class="text-xs font-bold text-gray-400">TOTAL TAGIHAN:</span>
            <span class="text-lg font-black text-gray-800">Rp{{ number_format($order->total_amount) }}</span>
        </div>
    </div>

    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/90 backdrop-blur-sm border-t border-gray-100 md:left-64 shadow-[0_-10px_20px_rgba(0,0,0,0.05)]">
        @if($order->status == 'on_delivery')
            <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="delivered">
                <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black shadow-xl hover:bg-indigo-700 transition-all">
                    SAYESAIKAN PENGANTARAN
                </button>
            </form>
        @else
            <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="on_delivery">
                <button type="submit" class="w-full bg-purple-600 text-white py-4 rounded-2xl font-black shadow-xl hover:bg-purple-700 transition-all">
                    KONFIRMASI: SAYA BERANGKAT
                </button>
            </form>
        @endif
    </div>
</div>
@endsection