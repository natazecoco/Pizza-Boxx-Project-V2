<div x-data="{ open: false }"
     class="bg-white p-5 rounded-xl shadow-md transition-all duration-300 transform hover:scale-[1.01] border border-gray-100">

    {{-- HEADER --}}
    <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
        <div class="flex-grow">
            <div class="flex items-center gap-2">
                <h3 class="text-lg font-bold text-gray-800">
                    #{{ $order->id }} - {{ $order->customer_name }}
                </h3>

                {{-- TIPE ORDER BADGE --}}
                @if($order->order_type === 'pickup')
                    <span class="px-2 py-0.5 text-[9px] font-bold bg-teal-100 text-teal-700 rounded-full uppercase">
                        PICKUP
                    </span>
                @else
                    <span class="px-2 py-0.5 text-[9px] font-bold bg-purple-100 text-purple-700 rounded-full uppercase">
                        DELIVERY
                    </span>
                @endif
            </div>

            {{-- SLA TIMER --}}
            <div class="flex items-center gap-2 mt-1">
                <span class="sla-timer-badge px-2 py-0.5 rounded font-bold uppercase tracking-wider flex items-center gap-1
                             bg-gray-100 text-gray-500 transition-all duration-500"
                      id="timer-{{ $order->id }}"
                      data-start="{{ $order->created_at->toIso8601String() }}"
                      data-status="{{ $order->status }}"
                      data-sla="{{ $order->status == 'pending' ? 300 : ($order->status == 'preparing' ? 900 : 1200) }}">
                    <i class="fas fa-hourglass-half text-[8px]"></i>
                    <span class="timer-text text-[10px]">0d</span>
                </span>

                <span class="text-[10px] text-gray-400 font-medium">
                    | {{ $order->created_at->format('H:i') }} WIB
                </span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            {{-- STATUS BADGE --}}
            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                @if($order->status == 'pending') bg-yellow-100 text-yellow-700 animate-pulse
                @elseif($order->status == 'accepted') bg-blue-100 text-blue-700
                @elseif($order->status == 'preparing') bg-orange-100 text-orange-700
                @elseif($order->status == 'ready_for_delivery' || $order->status == 'ready_for_pickup') bg-green-100 text-green-700
                @elseif($order->status == 'on_delivery') bg-purple-100 text-purple-700
                @elseif(in_array($order->status, ['completed','delivered'])) bg-gray-500 text-white
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

    {{-- RINGKASAN ITEM --}}
    <div class="mt-4 p-3 bg-red-50/50 rounded-lg border border-dashed border-red-200">
        <ul class="space-y-1">
            @foreach($order->orderItems->take(2) as $item)
                <li class="text-xs text-gray-700">
                    <span class="font-bold">{{ $item->quantity }}x</span> {{ $item->product_name }}
                </li>
            @endforeach

            @if($order->orderItems->count() > 2)
                <li class="text-[10px] text-gray-400 italic">
                    + {{ $order->orderItems->count() - 2 }} item lainnya...
                </li>
            @endif
        </ul>
    </div>

    {{-- DETAIL --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         class="mt-4 pt-4 border-t border-gray-100 space-y-3">

        <div class="grid grid-cols-2 gap-2 text-xs">
            <div>
                <p class="text-gray-400 uppercase font-bold text-[9px]">Tipe Pesanan</p>
                <p class="font-semibold text-gray-700">{{ ucfirst($order->order_type) }}</p>
            </div>

            <div>
                <p class="text-gray-400 uppercase font-bold text-[9px]">Alamat / Catatan</p>
                <p class="font-semibold text-gray-700">
                    {{ $order->delivery_address ?? 'Ambil di Toko' }}
                </p>
            </div>
        </div>

        <div>
            <h4 class="text-[10px] font-bold text-gray-400 uppercase mb-1">
                Detail Item Lengkap
            </h4>

            <ul class="bg-gray-50 p-2 rounded-md">
                @foreach($order->orderItems as $item)
                    <li class="text-xs py-1 border-b border-gray-200 last:border-0">
                        <span class="font-bold text-red-600">{{ $item->quantity }}x</span>
                        {{ $item->product_name }}

                        @if($item->options)
                            <span class="text-[10px] text-gray-400 block ml-5 italic">
                                ({{ is_array($item->options) ? implode(', ', $item->options) : $item->options }})
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-100">
        @switch($order->status)

            @case('pending')
                <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST" class="flex-grow">
                    @csrf
                    <input type="hidden" name="status" value="accepted">
                    <button class="w-full px-4 py-2 bg-red-600 text-white text-xs font-black rounded-lg hover:bg-red-700 uppercase">
                        <i class="fas fa-check mr-1"></i> Terima
                    </button>
                </form>
                @break

            @case('accepted')
            @case('preparing')
                <a href="{{ route('pegawai.orders.show', $order->id) }}"
                   class="flex-grow px-4 py-2 bg-orange-500 text-white text-xs font-black rounded-lg text-center hover:bg-orange-600 uppercase">
                    <i class="fas fa-fire mr-1"></i> Proses Masak
                </a>
                @break

            @case('ready_for_delivery')
            @case('ready_for_pickup')
                @if($order->order_type === 'pickup')
                    <button type="button"
                            @click="showPinModal = true;
                                    activeOrder = { id: '{{ $order->id }}', name: '{{ addslashes($order->customer_name) }}' };
                                    pin = ''"
                            class="flex-grow px-4 py-2 bg-teal-600 text-white text-xs font-black rounded-lg hover:bg-teal-700 uppercase">
                        <i class="fas fa-key mr-1"></i> Verifikasi PIN
                    </button>
                @else
                    <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST" class="flex-grow">
                        @csrf
                        <input type="hidden" name="status" value="on_delivery">
                        <button class="w-full px-4 py-2 bg-purple-600 text-white text-xs font-black rounded-lg hover:bg-purple-700 uppercase">
                            <i class="fas fa-motorcycle mr-1"></i> Kirim
                        </button>
                    </form>
                @endif
                @break

            @case('on_delivery')
                <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST" class="flex-grow">
                    @csrf
                    <input type="hidden" name="status" value="delivered">
                    <button class="w-full px-4 py-2 bg-indigo-600 text-white text-xs font-black rounded-lg hover:bg-indigo-700 uppercase">
                        <i class="fas fa-map-marker-alt mr-1"></i> Tiba
                    </button>
                </form>
                @break

            @default
                <span class="flex-grow px-4 py-2 text-gray-400 text-[10px] font-bold italic border rounded-lg text-center">
                    SELESAI
                </span>
        @endswitch

        <a href="{{ route('pegawai.orders.show', $order->id) }}"
           class="px-4 py-2 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg hover:bg-gray-200">
            Detail
        </a>
    </div>
</div>