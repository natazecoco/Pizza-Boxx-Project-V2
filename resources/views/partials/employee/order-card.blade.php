<div x-data="{ open: false }"
     class="bg-white p-5 rounded-xl shadow-md transition-all duration-300 transform hover:scale-[1.01] border border-gray-100">

    {{-- HEADER --}}
    <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
        <div class="flex-grow">
            <div class="flex items-center gap-2">
                <h3 class="text-lg font-bold text-gray-800">
                    #{{ $order->order_code ?? $order->id }} <span class="font-normal text-gray-500">|</span> {{ $order->customer_name }}
                </h3>

                {{-- TIPE ORDER BADGE --}}
                @if($order->order_type === 'pickup')
                    <span class="px-2 py-0.5 text-[9px] font-bold bg-teal-100 text-teal-700 rounded-full uppercase border border-teal-200">
                        <i class="fas fa-store mr-1"></i> PICKUP
                    </span>
                @else
                    <span class="px-2 py-0.5 text-[9px] font-bold bg-purple-100 text-purple-700 rounded-full uppercase border border-purple-200">
                        <i class="fas fa-motorcycle mr-1"></i> DELIVERY
                    </span>
                @endif
            </div>

            {{-- SLA TIMER --}}
            <div class="flex items-center gap-2 mt-1">
                <span class="sla-timer-badge px-2 py-0.5 rounded font-bold uppercase tracking-wider flex items-center gap-1
                             bg-gray-100 text-gray-500 transition-all duration-500 border border-gray-200"
                      id="timer-{{ $order->id }}"
                      data-start="{{ $order->created_at->toIso8601String() }}"
                      data-status="{{ $order->status }}"
                      data-sla="{{ $order->status == 'pending' ? 300 : ($order->status == 'preparing' ? 900 : 1200) }}">
                    <i class="fas fa-hourglass-half text-[8px]"></i>
                    <span class="timer-text text-[10px]">00:00</span>
                </span>

                <span class="text-[10px] text-gray-400 font-medium">
                    Masuk: {{ $order->created_at->format('H:i') }} WIB
                </span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            {{-- STATUS BADGE --}}
            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border
                @if($order->status == 'pending') bg-yellow-50 text-yellow-700 border-yellow-200 animate-pulse
                @elseif($order->status == 'accepted') bg-blue-50 text-blue-700 border-blue-200
                @elseif($order->status == 'preparing') bg-orange-50 text-orange-700 border-orange-200
                @elseif($order->status == 'ready_for_delivery' || $order->status == 'ready_for_pickup') bg-green-50 text-green-700 border-green-200
                @elseif($order->status == 'on_delivery') bg-purple-50 text-purple-700 border-purple-200
                @elseif(in_array($order->status, ['completed','delivered'])) bg-gray-100 text-gray-500 border-gray-200
                @endif">
                {{ str_replace('_', ' ', $order->status) }}
            </span>

            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                 :class="{ 'rotate-180': open }"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>

    {{-- RINGKASAN ITEM (PREVIEW) --}}
    <div class="mt-4 p-3 bg-slate-50 rounded-lg border border-dashed border-slate-200">
        <ul class="space-y-1">
            @foreach($order->orderItems->take(2) as $item)
                <li class="text-xs text-gray-700 flex justify-between">
                    <span><span class="font-bold text-gray-900">{{ $item->quantity }}x</span> {{ $item->product_name }}</span>
                </li>
            @endforeach

            @if($order->orderItems->count() > 2)
                <li class="text-[10px] text-gray-400 italic font-medium mt-1">
                    + {{ $order->orderItems->count() - 2 }} item lainnya...
                </li>
            @endif
        </ul>
    </div>

    {{-- DETAIL DROPDOWN --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         class="mt-4 pt-4 border-t border-gray-100 space-y-4">

        {{-- INFO PENGIRIMAN LENGKAP --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs bg-yellow-50/50 p-3 rounded-xl border border-yellow-100">
            {{-- Kiri: Kontak --}}
            <div>
                <p class="text-gray-400 uppercase font-black text-[9px] tracking-widest mb-1">CUSTOMER</p>
                <p class="font-bold text-gray-800">{{ $order->customer_name }}</p>
                <p class="text-gray-500">{{ $order->customer_phone }}</p>
            </div>

            {{-- Kanan: Alamat & Maps --}}
            <div>
                <p class="text-gray-400 uppercase font-black text-[9px] tracking-widest mb-1">LOKASI / ALAMAT</p>
                <p class="font-bold text-gray-800 leading-snug">
                    {{ $order->delivery_address ?? 'Ambil di Toko (Pickup)' }}
                </p>
                
                {{-- Tampilkan Catatan Jika Ada --}}
                @if($order->delivery_notes)
                    <div class="mt-1 flex items-start gap-1 text-orange-600">
                        <i class="fas fa-sticky-note mt-0.5 text-[10px]"></i>
                        <span class="italic font-bold">"{{ $order->delivery_notes }}"</span>
                    </div>
                @endif

                {{-- Tampilkan Tombol Maps Jika Ada Koordinat --}}
                @if($order->latitude && $order->longitude)
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $order->latitude }},{{ $order->longitude }}" 
                       target="_blank"
                       class="inline-flex items-center gap-1 mt-2 bg-white border border-gray-200 px-2 py-1 rounded shadow-sm text-[10px] font-black uppercase text-blue-600 hover:text-blue-800 hover:border-blue-300 transition-all">
                        <i class="fas fa-map-marked-alt"></i> Buka Maps
                    </a>
                @endif
            </div>
        </div>

        {{-- DETAIL ITEM --}}
        <div>
            <h4 class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">
                DAFTAR ITEM LENGKAP
            </h4>

            <ul class="bg-white border border-gray-100 rounded-xl divide-y divide-gray-100 overflow-hidden">
                @foreach($order->orderItems as $item)
                    <li class="p-3 text-xs hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-black text-brand-red text-sm mr-1">{{ $item->quantity }}x</span>
                                <span class="font-bold text-gray-800 text-sm">{{ $item->product_name }}</span>
                                
                                {{-- PERBAIKAN DI SINI (Extract nama opsi) --}}
                                @if($item->options && is_array($item->options))
                                    <div class="mt-1 ml-6 text-[10px] text-gray-500 font-medium">
                                        @foreach($item->options as $opt)
                                            {{-- Cek apakah $opt itu array (punya 'name') atau string --}}
                                            <span class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-600 border border-gray-200 inline-block mr-1 mb-1">
                                                {{ is_array($opt) ? ($opt['name'] ?? '-') : $opt }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <span class="font-bold text-gray-900">
                                Rp {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
            
            {{-- TOTAL --}}
            <div class="flex justify-end mt-2 items-center gap-3">
                <span class="text-[10px] font-bold text-gray-400 uppercase">Total Pesanan</span>
                <span class="text-lg font-black text-brand-red">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- ACTION BUTTONS (SAMA SEPERTI SEBELUMNYA) --}}
    <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-100">
        @switch($order->status)

            @case('pending')
                <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST" class="flex-grow">
                    @csrf
                    <input type="hidden" name="status" value="accepted">
                    <button class="w-full px-4 py-3 bg-brand-red text-white text-xs font-black rounded-xl hover:bg-red-700 uppercase shadow-lg shadow-red-100 transition-all">
                        <i class="fas fa-check mr-1"></i> Terima Pesanan
                    </button>
                </form>
                @break

            @case('accepted')
            @case('preparing')
                <a href="{{ route('pegawai.orders.show', $order->id) }}"
                   class="flex-grow px-4 py-3 bg-orange-500 text-white text-xs font-black rounded-xl text-center hover:bg-orange-600 uppercase shadow-lg shadow-orange-100 transition-all">
                    <i class="fas fa-fire mr-1"></i> Mulai Masak / Detail
                </a>
                @break

            @case('ready_for_delivery')
            @case('ready_for_pickup')
                @if($order->order_type === 'pickup')
                    <button type="button"
                            @click="showPinModal = true;
                                    activeOrder = { id: '{{ $order->id }}', name: '{{ addslashes($order->customer_name) }}' };
                                    pin = ''"
                            class="flex-grow px-4 py-3 bg-teal-600 text-white text-xs font-black rounded-xl hover:bg-teal-700 uppercase shadow-lg shadow-teal-100 transition-all">
                        <i class="fas fa-key mr-1"></i> Verifikasi PIN Customer
                    </button>
                @else
                    <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST" class="flex-grow">
                        @csrf
                        <input type="hidden" name="status" value="on_delivery">
                        <button class="w-full px-4 py-3 bg-purple-600 text-white text-xs font-black rounded-xl hover:bg-purple-700 uppercase shadow-lg shadow-purple-100 transition-all">
                            <i class="fas fa-motorcycle mr-1"></i> Kirim Pesanan
                        </button>
                    </form>
                @endif
                @break

            @case('on_delivery')
                <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST" class="flex-grow">
                    @csrf
                    <input type="hidden" name="status" value="delivered">
                    <button class="w-full px-4 py-3 bg-indigo-600 text-white text-xs font-black rounded-xl hover:bg-indigo-700 uppercase shadow-lg shadow-indigo-100 transition-all">
                        <i class="fas fa-map-marker-alt mr-1"></i> Konfirmasi Tiba
                    </button>
                </form>
                @break

            @default
                <span class="flex-grow px-4 py-3 text-gray-400 text-[10px] font-black uppercase border border-gray-200 rounded-xl text-center bg-gray-50">
                    Pesanan Selesai
                </span>
        @endswitch

        <a href="{{ route('pegawai.orders.show', $order->id) }}"
           class="px-4 py-3 bg-white border-2 border-gray-100 text-gray-600 text-xs font-black rounded-xl hover:bg-gray-50 hover:text-brand-red hover:border-red-100 transition-all">
            <i class="fas fa-eye"></i>
        </a>
    </div>
</div>