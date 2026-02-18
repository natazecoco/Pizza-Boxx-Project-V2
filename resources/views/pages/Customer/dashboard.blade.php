@extends('layouts.customer')

@section('title', 'Pesanan Saya')

@section('content')
{{-- --- 1. HERO SECTION --- --}}
<section class="bg-brand-red pt-32 pb-24 relative overflow-hidden rounded-b-[3rem] shadow-xl z-10">
    <div class="absolute inset-0 bg-pizza-pattern opacity-10"></div>
    
    <div class="container mx-auto px-6 lg:px-12 relative z-10 text-center">
        <span class="text-brand-kraft font-black text-[10px] uppercase tracking-widest mb-2 block animate-fade-in-down">Order History</span>
        <h1 class="text-5xl md:text-7xl font-black text-white italic uppercase tracking-tighter leading-none mb-8">
            PESANAN <span class="text-brand-kraft">SAYA</span>
        </h1>
        
        {{-- Stats Bento --}}
        <div class="inline-flex flex-wrap justify-center gap-4">
            <div class="bg-gray-900 text-white px-6 py-3 rounded-2xl border-2 border-transparent shadow-[4px_4px_0px_0px_rgba(0,0,0,0.2)]">
                <p class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-1">Total Order</p>
                <p class="text-xl font-black leading-none">{{ $orders->count() }}</p>
            </div>
            <div class="bg-white text-gray-900 px-6 py-3 rounded-2xl border-2 border-transparent shadow-[4px_4px_0px_0px_rgba(0,0,0,0.2)]">
                <p class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-1">Sedang Aktif</p>
                <p class="text-xl font-black text-brand-red leading-none">{{ $orders->whereIn('status', ['pending', 'dapur', 'on_delivery'])->count() }}</p>
            </div>
        </div>
    </div>
</section>

{{-- --- 2. CONTENT LIST --- --}}
<section class="bg-slate-50 min-h-screen pb-24 pt-12">
    <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
        
        <div class="space-y-6">
            @forelse($orders as $order)
                @php
                    $statusColors = [
                        'pending'     => ['bg' => 'bg-yellow-100', 'border' => 'border-yellow-200', 'text' => 'text-yellow-700', 'icon' => 'fa-clock'],
                        'dapur'       => ['bg' => 'bg-orange-100', 'border' => 'border-orange-200', 'text' => 'text-orange-700', 'icon' => 'fa-fire'],
                        'on_delivery' => ['bg' => 'bg-blue-100',   'border' => 'border-blue-200',   'text' => 'text-blue-700',   'icon' => 'fa-motorcycle'],
                        'delivered'   => ['bg' => 'bg-green-100',  'border' => 'border-green-200',  'text' => 'text-green-700',  'icon' => 'fa-check-circle'],
                        'completed'   => ['bg' => 'bg-gray-100',   'border' => 'border-gray-200',   'text' => 'text-gray-700',   'icon' => 'fa-flag-checkered'],
                        'cancelled'   => ['bg' => 'bg-red-100',    'border' => 'border-red-200',    'text' => 'text-red-700',    'icon' => 'fa-times-circle'],
                    ];
                    $s = strtolower($order->status);
                    $conf = $statusColors[$s] ?? ['bg' => 'bg-gray-100', 'border' => 'border-gray-200', 'text' => 'text-gray-500', 'icon' => 'fa-circle'];
                @endphp

                {{-- CARD PESANAN (Neo-Brutalism Style) --}}
                <div class="group bg-white rounded-[2.5rem] p-6 lg:p-8 border-2 border-transparent hover:border-gray-900 shadow-sm hover:shadow-[6px_6px_0px_0px_rgba(220,38,38,1)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                    
                    {{-- Decoration bg on hover --}}
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-bl-[5rem] -z-0 transition-colors group-hover:bg-red-50"></div>

                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        
                        {{-- Bagian Kiri: Identitas --}}
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 border-2 border-gray-100 text-gray-300 group-hover:border-brand-red group-hover:bg-brand-red group-hover:text-white flex items-center justify-center transition-all duration-300">
                                <i class="fas fa-pizza-slice text-2xl"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-xl font-black text-gray-900 italic uppercase tracking-tighter leading-none">#{{ $order->order_code ?? $order->id }}</h3>
                                    {{-- Status Badge --}}
                                    <span class="{{ $conf['bg'] }} {{ $conf['text'] }} border {{ $conf['border'] }} px-2.5 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                        <i class="fas {{ $conf['icon'] }}"></i> {{ $order->status }}
                                    </span>
                                </div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    {{ $order->created_at->format('d M Y') }} • {{ $order->created_at->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        {{-- Bagian Kanan: Harga & Tombol --}}
                        <div class="flex flex-row md:flex-col items-center md:items-end justify-between gap-2 md:gap-4 border-t md:border-t-0 pt-4 md:pt-0 border-dashed border-gray-100">
                            <div class="text-left md:text-right">
                                <p class="text-[9px] font-bold text-gray-300 uppercase tracking-widest mb-0.5 leading-none">Total Bayar</p>
                                <p class="text-xl font-black text-brand-red italic leading-none">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                            
                            {{-- Tombol Lacak (Style Konsisten) --}}
                            <a href="{{ route('user.order.show', $order->id) }}" 
                               class="group/btn bg-gray-900 text-white text-[10px] font-black uppercase tracking-[0.2em] px-6 py-3 rounded-xl border-2 border-transparent transition-all 
                                      shadow-[3px_3px_0px_0px_rgba(220,38,38,1)] 
                                      hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] 
                                      hover:translate-x-[1px] hover:translate-y-[1px] 
                                      flex items-center gap-2">
                                Detail <i class="fas fa-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>

                    </div>
                </div>

            @empty
                {{-- EMPTY STATE --}}
                <div class="bg-white rounded-[3rem] py-24 text-center border-2 border-dashed border-gray-200">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300 text-4xl border-4 border-slate-100">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter">Belum ada pesanan</h3>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-2 mb-8">Perut lapar? Pizza enak menunggu!</p>
                    <a href="{{ route('menu.index') }}" class="inline-flex bg-brand-red text-white font-black py-4 px-10 rounded-2xl text-xs uppercase tracking-widest hover:bg-gray-900 transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[1px] hover:translate-y-[1px] border-2 border-transparent">
                        Pesan Sekarang
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</section>
@endsection