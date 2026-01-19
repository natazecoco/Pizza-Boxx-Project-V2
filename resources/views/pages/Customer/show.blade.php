@extends('layouts.customer')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    {{-- Header: Judul & Status --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Pesanan #{{ $order->id }}</h1>
            <p class="text-gray-500 text-sm">Dipesan pada {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="px-4 py-2 bg-orange-100 text-orange-700 rounded-full text-sm font-bold">
            {{ $order->user_friendly_status }}
        </div>
    </div>

    {{-- Kode PIN (Hanya untuk Pickup) --}}
    @if($order->order_type === 'pickup' && !in_array($order->status, ['completed', 'cancelled']))
    <div class="mb-8 bg-gradient-to-br from-red-600 to-orange-500 rounded-2xl p-6 text-white shadow-xl text-center">
        <h3 class="text-sm font-bold uppercase tracking-widest opacity-80 mb-2">Kode Pengambilan (PIN)</h3>
        <div class="bg-white/20 backdrop-blur-sm rounded-xl py-4 border border-white/30">
            <span class="text-5xl font-black tracking-[0.5em] ml-[0.5em]">
                {{ $order->pickup_pin }}
            </span>
        </div>
        <p class="mt-4 text-xs font-medium opacity-90">
            <i class="fas fa-info-circle mr-1"></i> Tunjukkan kode di atas kepada kasir untuk mengambil pesanan Anda.
        </p>
    </div>
    @endif

    {{-- Progress Tracker --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <h2 class="text-lg font-bold mb-6 text-center">Status Pesanan</h2>
        <div class="flex items-center justify-between relative">
            <div class="absolute top-5 left-0 w-full h-1 bg-gray-100 -z-0"></div>
            
            <div class="relative z-10 flex flex-col items-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ in_array($order->status, ['accepted', 'preparing', 'ready_for_delivery', 'ready_for_pickup', 'on_delivery', 'delivered', 'completed']) ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                    <i class="fas fa-receipt"></i>
                </div>
                <p class="mt-2 text-[10px] md:text-xs font-bold uppercase tracking-wider">Diterima</p>
            </div>

            <div class="relative z-10 flex flex-col items-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ in_array($order->status, ['preparing', 'ready_for_delivery', 'ready_for_pickup', 'on_delivery', 'delivered', 'completed']) ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                    <i class="fas fa-fire"></i>
                </div>
                <p class="mt-2 text-[10px] md:text-xs font-bold uppercase tracking-wider">Dimasak</p>
            </div>

            <div class="relative z-10 flex flex-col items-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ in_array($order->status, ['ready_for_delivery', 'ready_for_pickup', 'on_delivery', 'delivered', 'completed']) ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                    <i class="fas {{ $order->order_type === 'pickup' ? 'fa-store' : 'fa-truck' }}"></i>
                </div>
                <p class="mt-2 text-[10px] md:text-xs font-bold uppercase tracking-wider">
                    {{ $order->order_type === 'pickup' ? 'Siap Ambil' : 'Diantar' }}
                </p>
            </div>

            <div class="relative z-10 flex flex-col items-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $order->status === 'completed' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                    <i class="fas fa-check-double"></i>
                </div>
                <p class="mt-2 text-[10px] md:text-xs font-bold uppercase tracking-wider">Selesai</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Sisi Kiri: Ringkasan Menu --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-shopping-bag text-orange-500"></i> Ringkasan Menu
            </h3>
            
            {{-- Tombol Konfirmasi (Khusus Delivery yang sudah sampai) --}}
            @if($order->order_type === 'delivery' && $order->status === 'delivered')
                <div class="mb-6 bg-orange-50 border-2 border-orange-200 rounded-2xl p-6 text-center shadow-lg animate-bounce">
                    <h3 class="text-lg font-bold text-orange-800 mb-2">Pizza Sudah Sampai?</h3>
                    <p class="text-sm text-orange-700 mb-4">Silakan konfirmasi jika Anda sudah menerima pesanan dengan baik.</p>
                    <form action="{{ route('user.order.complete', $order->id) }}" method="POST" id="completeOrderForm">
                        @csrf
                        <button type="button" onclick="confirmComplete()" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-black py-3 px-6 rounded-xl transition-all shadow-md">
                            PESANAN SAYA TERIMA
                        </button>
                    </form>
                </div>
            @endif

            <ul class="space-y-4">
                @foreach($order->orderItems as $item)
                <li class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden">
                            <img src="{{ asset('storage/' . $item->product->image_path) }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-semibold text-sm">{{ $item->product->name }}</p>
                            @if($item->options)
                                <p class="text-[10px] text-orange-600 font-bold uppercase tracking-tight">
                                    {{ is_array($item->options) ? implode(' • ', $item->options) : $item->options }}
                                </p>
                            @endif
                            {{-- Tips: Cek nama kolom 'unit_price' atau 'price' di sini --}}
                            <p class="text-xs text-gray-500">{{ $item->quantity }}x @ Rp {{ number_format($item->unit_price ?? $item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    {{-- Subtotal per item --}}
                    <p class="font-bold text-sm">Rp {{ number_format(($item->unit_price ?? $item->price) * $item->quantity, 0, ',', '.') }}</p>
                </li>
                @endforeach

                {{-- Total Pembayaran (HARUS DI LUAR LOOP FOREACH) --}}
                <li class="pt-4 border-t-2 border-dashed border-gray-100 flex justify-between items-center">
                    <span class="font-bold text-gray-900">Total Pembayaran</span>
                    <span class="text-xl font-black text-red-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </li>
            </ul>
        </div>

        {{-- Sisi Kanan: Alamat/Cabang --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold mb-4 flex items-center gap-2">
                    <i class="fas {{ $order->order_type === 'delivery' ? 'fa-map-marked-alt' : 'fa-store' }} text-orange-500"></i> 
                    {{ $order->order_type === 'delivery' ? 'Alamat Pengiriman' : 'Informasi Cabang' }}
                </h3>
                <div class="text-sm text-gray-600 leading-relaxed">
                    @if($order->order_type === 'delivery')
                        <p class="font-bold text-gray-900 mb-1 italic">Dikirim ke Rumah/Lokasi:</p>
                        <p>{{ $order->delivery_address }}</p>
                    @else
                        <p class="font-bold text-gray-900 mb-1">{{ $order->location->name ?? 'Cabang Pizza Boxx' }}</p>
                        <p>{{ $order->location->address ?? 'Alamat belum tersedia' }}</p>
                    @endif
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-gray-50">
                <a href="https://wa.me/{{ $order->location->phone ?? '' }}" target="_blank" class="flex items-center justify-center gap-2 text-xs font-bold text-gray-400 hover:text-green-600 transition-colors">
                    <i class="fab fa-whatsapp text-lg"></i> Hubungi toko jika butuh bantuan
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmComplete() {
        Swal.fire({
            title: 'Selesaikan Pesanan?',
            text: "Pastikan pizza sudah Anda terima dalam kondisi baik ya!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ea580c',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Selesai!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('completeOrderForm').submit();
            }
        })
    }
</script>
@endsection