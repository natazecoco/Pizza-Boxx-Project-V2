@extends('layouts.employee')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Halo, Pegawai Pizza Boxx! 👋</h2>
        <p class="text-gray-500 mt-1">Berikut adalah ringkasan performa toko hari ini, {{ date('d F Y') }}.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Cuan Hari Ini</p>
                    <h3 class="text-2xl font-black text-gray-800 mt-1">Rp{{ number_format($todaySales) }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-xl text-green-600">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pesanan Masuk</p>
                    <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $todayOrdersCount }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-xl text-blue-600">
                    <i class="fas fa-shopping-basket text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Antrean Masak</p>
                    <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $pendingOrdersCount }}</h3>
                </div>
                <div class="bg-red-100 p-3 rounded-xl text-red-600">
                    <i class="fas fa-fire text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sedang Diantar</p>
                    <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $onDeliveryCount }}</h3>
                </div>
                <div class="bg-purple-100 p-3 rounded-xl text-purple-600">
                    <i class="fas fa-truck text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800 italic underline decoration-red-500 decoration-4">Pesanan Terbaru</h3>
                <a href="{{ route('pegawai.orders.index') }}" class="text-xs font-bold text-red-600 hover:underline">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase">
                            <th class="pb-4">Order ID</th>
                            <th class="pb-4">Pelanggan</th>
                            <th class="pb-4">Tipe</th>
                            <th class="pb-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($recentOrders as $order)
                        <tr class="border-t border-gray-50">
                            <td class="py-4 font-bold">#{{ $order->id }}</td>
                            <td class="py-4">{{ $order->customer_name }}</td>
                            <td class="py-4"><span class="px-2 py-1 bg-gray-100 rounded text-[10px] font-bold uppercase">{{ $order->order_type }}</span></td>
                            <td class="py-4">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold 
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($order->status == 'completed' || $order->status == 'delivered') bg-green-100 text-green-700
                                    @else bg-blue-100 text-blue-700 @endif">
                                    {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-red-600 rounded-3xl p-8 text-white shadow-xl shadow-red-200">
            <h3 class="text-xl font-bold mb-4 italic">Butuh Bantuan?</h3>
            <p class="text-sm opacity-80 mb-8 leading-relaxed">Kelola pesanan dengan cepat untuk menjaga kepuasan pelanggan Pizza Boxx.</p>
            
            <div class="space-y-4">
                <a href="{{ route('pegawai.orders.index') }}" class="flex items-center justify-between bg-white/20 p-4 rounded-2xl hover:bg-white/30 transition-all border border-white/20">
                    <span class="font-bold">Daftar Pesanan</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
                <a href="{{ route('pegawai.deliveries.index') }}" class="flex items-center justify-between bg-white/20 p-4 rounded-2xl hover:bg-white/30 transition-all border border-white/20">
                    <span class="font-bold">Daftar Pengantaran</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection