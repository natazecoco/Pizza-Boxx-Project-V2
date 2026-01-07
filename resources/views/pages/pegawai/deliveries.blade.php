@extends('layouts.employee')

@section('content')
<div class="container mx-auto py-10 px-2 md:px-0">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" /></svg>
            <h1 class="text-2xl font-extrabold text-red-600 tracking-tight">Daftar Pengantaran</h1>
        </div>
        <a href="{{ route('pegawai.deliveries.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow font-semibold transition-all duration-200">+ Buat Pengantaran</a>
    </div>

    {{-- Tampilan Daftar Pengantaran dalam bentuk Card --}}
    <div class="space-y-4">
        @foreach($deliveries as $delivery)
            <div x-data="{ open: false }" class="bg-white p-6 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-[1.01]">
                <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                    <div class="flex-grow">
                        <h3 class="text-lg font-bold text-gray-800">Pengantaran #{{ $delivery->id }}</h3>
                        <p class="text-sm text-gray-500">Order: #{{ $delivery->order_id }}</p>
                    </div>
                    <div class="flex-shrink-0 flex items-center gap-4">
                        @if($delivery->deliveryEmployee)
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ $delivery->deliveryEmployee->name }}
                            </span>
                        @else
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-400">-</span>
                        @endif
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                            {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                        </span>
                        <button class="text-gray-400 hover:text-gray-600 transition-colors" :class="{ 'rotate-180': open }">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                    </div>
                </div>
                
                {{-- Detail Pengantaran (Dropdown) --}}
                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-y-0" x-transition:enter-end="opacity-100 scale-y-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-y-100" x-transition:leave-end="opacity-0 scale-y-0" class="mt-4 pt-4 border-t border-gray-200 space-y-3">
                    <p class="text-gray-600"><strong>Pelanggan:</strong> {{ $delivery->order ? $delivery->order->customer_name : '-' }}</p>
                    <p class="text-gray-600"><strong>Telepon:</strong> {{ $delivery->order ? $delivery->order->customer_phone : '-' }}</p>
                    <div class="grid grid-cols-2 gap-4">
                        <p class="text-gray-600"><strong>Ditugaskan:</strong> {{ $delivery->assigned_at ? $delivery->assigned_at->format('d M Y H:i') : '-' }}</p>
                        <p class="text-gray-600"><strong>Diambil:</strong> {{ $delivery->picked_up_at ? $delivery->picked_up_at->format('d M Y H:i') : '-' }}</p>
                        <p class="text-gray-600"><strong>Sampai:</strong> {{ $delivery->delivered_at ? $delivery->delivered_at->format('d M Y H:i') : '-' }}</p>
                    </div>
                    <p class="text-gray-600"><strong>Catatan:</strong> {{ $delivery->notes ?? '-' }}</p>

                    {{-- Tombol Aksi --}}
                    <div class="flex justify-end mt-4">
                        <a href="{{ route('pegawai.deliveries.detail', $delivery->id) }}" class="px-4 py-2 bg-yellow-500 text-white font-bold rounded-lg shadow hover:bg-yellow-600 transition-colors">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection