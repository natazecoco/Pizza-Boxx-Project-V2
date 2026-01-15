@extends('layouts.employee')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Daftar Pengantaran 🚚</h2>
        <p class="text-gray-500 mt-1">Daftar pesanan yang harus segera diantar ke pelanggan.</p>
    </div>

    @if($deliveries->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-dashed border-gray-300">
            <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-box-open text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Tidak ada pengantaran aktif</h3>
            <p class="text-gray-500">Semua pizza delivery sudah sampai ke tujuan atau belum ada pesanan baru.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($deliveries as $order)
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden flex flex-col">
                    
                    <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-400">#{{ $order->id }}</span>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase 
                            {{ $order->status == 'on_delivery' ? 'bg-purple-600 text-white animate-pulse' : 'bg-blue-100 text-blue-700' }}">
                            {{ str_replace('_', ' ', $order->status) }}
                        </span>
                    </div>

                    <div class="p-5 flex-grow">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $order->customer_name }}</h3>
                        <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                            <i class="fas fa-map-marker-alt text-red-500 mr-1"></i> {{ $order->delivery_address }}
                        </p>
                        <p class="text-sm font-black text-gray-800">Rp{{ number_format($order->total_amount) }}</p>
                    </div>

                    <div class="p-4 bg-white border-t grid grid-cols-2 gap-2">
                        {{-- Tombol untuk pindah ke halaman DETAIL --}}
                        <a href="{{ route('pegawai.deliveries.show', $order->id) }}" 
                           class="flex items-center justify-center bg-gray-100 text-gray-600 py-3 rounded-xl text-xs font-bold hover:bg-gray-200 transition-all">
                            Lihat 
                        </a>

                        {{-- Tombol Update Status Langsung --}}
                        @if($order->status == 'on_delivery')
                            <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="delivered">
                                <button type="submit" class="w-full bg-green-500 text-white py-3 rounded-xl text-xs font-bold hover:bg-green-600 transition-all">
                                    Selesai
                                </button>
                            </form>
                        @else
                            <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="on_delivery">
                                <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-xl text-xs font-bold hover:bg-purple-700 transition-all">
                                    Mulai Antar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection