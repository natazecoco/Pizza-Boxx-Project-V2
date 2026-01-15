@extends('layouts.employee')
@section('content')
<div class="container mx-auto py-10 px-2 md:px-0" x-data="{ filter: 'all' }"> {{-- Tambahkan x-data di sini --}}

<!-- Header Halaman -->
    <div class="mb-8">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Daftar Pesanan 🍕</h2>
        <p class="text-gray-500 mt-1">Pantau dan kelola proses pembuatan pizza pelanggan secara real-time.</p>
    </div>

    <!-- Tombol Filter -->
    <div class="mb-8 flex flex-wrap items-center gap-3">
        <button @click="filter = 'all'" 
                :class="filter === 'all' ? 'bg-red-600 text-white shadow-lg scale-105' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'"
                class="px-6 py-2 rounded-full border text-sm font-bold transition-all duration-200 flex items-center gap-2">
            Semua <span class="bg-white/20 px-2 py-0.5 rounded-full text-[10px]">{{ $orders->count() }}</span>
        </button>
        
        <button @click="filter = 'delivery'" 
                :class="filter === 'delivery' ? 'bg-blue-600 text-white shadow-lg scale-105' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'"
                class="px-6 py-2 rounded-full border text-sm font-bold transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-truck"></i> Delivery <span class="bg-white/20 px-2 py-0.5 rounded-full text-[10px]">{{ $orders->where('order_type', 'delivery')->count() }}</span>
        </button>
        
        <button @click="filter = 'pickup'" 
                :class="filter === 'pickup' ? 'bg-green-600 text-white shadow-lg scale-105' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'"
                class="px-6 py-2 rounded-full border text-sm font-bold transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-store"></i> Pickup <span class="bg-white/20 px-2 py-0.5 rounded-full text-[10px]">{{ $orders->where('order_type', 'pickup')->count() }}</span>
        </button>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-red-50 p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-red-700">Pending</h2>
                <p class="text-4xl font-bold text-red-600 mt-2">{{ $orders->where('status', 'pending')->count() }}</p>
            </div>
            <div class="p-3 bg-red-200 rounded-full"><i class="fas fa-clock text-red-600 text-2xl"></i></div>
        </div>
        <div class="bg-yellow-50 p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-yellow-700">Proses</h2>
                <p class="text-4xl font-bold text-yellow-600 mt-2">{{ $orders->whereIn('status', ['accepted', 'preparing', 'ready_for_delivery', 'on_delivery'])->count() }}</p>
            </div>
            <div class="p-3 bg-yellow-200 rounded-full"><i class="fas fa-spinner fa-spin text-yellow-600 text-2xl"></i></div>
        </div>
        <div class="bg-green-50 p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-green-700">Selesai</h2>
                <p class="text-4xl font-bold text-green-600 mt-2">{{ $orders->whereIn('status', ['completed', 'delivered'])->count() }}</p>
            </div>
            <div class="p-3 bg-green-200 rounded-full"><i class="fas fa-check-double text-green-600 text-2xl"></i></div>
        </div>
    </div>
    
    <!-- Daftar Pesanan -->
    <div class="mt-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Kolom Pending -->
            <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-red-500">
                <h3 class="text-lg font-bold text-red-700 mb-4 uppercase tracking-wider">Pending</h3>
                <div class="space-y-4">
                    @foreach($orders->where('status', 'pending') as $order)
                        {{-- Logika filter Alpine.js --}}
                        <div x-show="filter === 'all' || filter === '{{ $order->order_type }}'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95">
                            @include('partials.employee.order-card', ['order' => $order])
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Kolom Dalam Proses -->
            <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-yellow-500">
                <h3 class="text-lg font-bold text-yellow-700 mb-4 uppercase tracking-wider">Dalam Proses</h3>
                <div class="space-y-4">
                    @foreach($orders->whereIn('status', ['accepted', 'preparing', 'ready_for_delivery', 'on_delivery']) as $order)
                        <div x-show="filter === 'all' || filter === '{{ $order->order_type }}'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95">
                            @include('partials.employee.order-card', ['order' => $order])
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Kolom Selesai -->
            <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-green-500">
                <h3 class="text-lg font-bold text-green-700 mb-4 uppercase tracking-wider">Selesai</h3>
                <div class="space-y-4">
                    @foreach($orders->whereIn('status', ['completed', 'delivered']) as $order)
                        <div x-show="filter === 'all' || filter === '{{ $order->order_type }}'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95">
                            @include('partials.employee.order-card', ['order' => $order])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Script untuk refresh otomatis -->
@push('scripts')
<script>
    // Refresh halaman otomatis setiap 60 detik (60000 milidetik)
    // Agar status "SLA Timer" dan antrean selalu up-to-date
    setTimeout(function(){
       window.location.reload(1);
    }, 10000); 
</script>
@endpush