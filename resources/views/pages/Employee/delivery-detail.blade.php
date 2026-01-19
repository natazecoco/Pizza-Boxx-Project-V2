@extends('layouts.employee')

@section('content')
{{-- Tambahkan x-data untuk kontrol Modal dan PIN --}}
<div class="container mx-auto px-4 py-6 max-w-lg" 
     x-data="{ 
        showPinModal: false, 
        pin: '', 
        orderId: '{{ $order->id }}',
        customerName: '{{ $order->customer_name }}'
     }">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('pegawai.deliveries.index') }}" class="bg-white p-3 rounded-2xl shadow-sm text-gray-600 hover:text-red-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-xl font-bold text-gray-800">Navigasi Pengantaran</h2>
    </div>

    {{-- Card Informasi Pelanggan --}}
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

            {{-- Tombol Navigasi --}}
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

    {{-- Daftar Item (Checklist) --}}
    <div class="bg-gray-50 rounded-3xl p-6 border border-gray-200 mb-24">
        <h3 class="text-xs font-bold text-gray-400 uppercase mb-4 tracking-widest">Cek Barang Bawaan:</h3>
        <ul class="space-y-3">
            @foreach($order->orderItems as $item)
            <li class="flex justify-between items-center bg-white p-3 rounded-xl border border-gray-100">
                <span class="text-sm font-bold text-gray-700">
                    <span class="text-red-600">{{ $item->quantity }}x</span> {{ $item->product_name }}
                </span>
                <input type="checkbox" class="rounded text-red-600 focus:ring-red-500 w-5 h-5 border-gray-300">
            </li>
            @endforeach
        </ul>
    </div>

    {{-- Fixed Action Button di Bagian Bawah --}}
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/90 backdrop-blur-sm border-t border-gray-100 md:left-64 shadow-[0_-10px_20px_rgba(0,0,0,0.05)] z-50">
        @if($order->status == 'on_delivery')
            {{-- Tombol ini sekarang memicu Modal PIN Alpine.js --}}
            <button @click="showPinModal = true" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black shadow-xl hover:bg-indigo-700 transition-all uppercase tracking-tighter">
                <i class="fas fa-key mr-2"></i> Konfirmasi PIN & Selesai
            </button>
        @else
            <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="on_delivery">
                <button type="submit" class="w-full bg-purple-600 text-white py-4 rounded-2xl font-black shadow-xl hover:bg-purple-700 transition-all uppercase tracking-tighter">
                    <i class="fas fa-motorcycle mr-2"></i> Konfirmasi: Saya Berangkat
                </button>
            </form>
        @endif
    </div>

    {{-- MODAL PIN (Sama dengan di KDS/orders.blade.php) --}}
    <div x-show="showPinModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4"
         style="display: none;">
        
        <div class="bg-white rounded-3xl w-full max-w-sm shadow-2xl overflow-hidden transform transition-all"
             @click.away="showPinModal = false">
            
            <div class="bg-gray-800 p-6 text-white text-center">
                <p class="text-xs font-bold uppercase tracking-widest text-red-400">Verifikasi Kedatangan</p>
                <h3 class="text-xl font-black italic">#{{ $order->id }} - {{ $order->customer_name }}</h3>
            </div>

            <div class="p-8">
                <div class="mb-6">
                    <div class="bg-gray-100 rounded-2xl p-4 border-2 border-gray-200 text-center">
                        <div class="text-4xl font-black tracking-[0.3em] text-red-600 h-10" x-text="pin"></div>
                        <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold">Minta 6-Digit PIN dari Pelanggan</p>
                    </div>
                </div>

                {{-- NUMPAD --}}
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <template x-for="n in [1,2,3,4,5,6,7,8,9]">
                        <button @click="if(pin.length < 6) pin += n" class="h-14 bg-gray-50 hover:bg-red-50 rounded-xl font-black text-xl text-gray-700 transition-colors border border-gray-100">
                            <span x-text="n"></span>
                        </button>
                    </template>
                    <button @click="pin = ''" class="h-14 bg-red-100 text-red-600 rounded-xl font-bold flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                    <button @click="if(pin.length < 6) pin += '0'" class="h-14 bg-gray-50 rounded-xl font-black text-xl text-gray-700 border border-gray-100">0</button>
                    <button @click="submitDeliveryPin('{{ $order->id }}', pin)" 
                            :disabled="pin.length < 6" 
                            class="h-14 bg-green-600 text-white rounded-xl font-bold flex items-center justify-center disabled:opacity-50">
                        <i class="fas fa-check"></i>
                    </button>
                </div>

                <button @click="showPinModal = false" class="w-full text-gray-400 font-bold text-sm hover:text-gray-600 transition-colors">
                    BATAL
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function submitDeliveryPin(orderId, pinCode) {
        if (pinCode.length < 6) return;
        
        Swal.fire({ title: 'Memverifikasi...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        fetch("{{ route('pegawai.qr.verify') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ order_id: orderId, pin: pinCode })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ 
                    icon: 'success', 
                    title: 'Berhasil!', 
                    text: 'Pesanan telah diterima pelanggan.', 
                    timer: 2000, 
                    showConfirmButton: false 
                }).then(() => {
                    // Redirect kembali ke daftar pengantaran
                    window.location.href = "{{ route('pegawai.deliveries.index') }}";
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
            }
        });
    }
</script>
@endpush