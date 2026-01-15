<div x-data="{ open: false }" class="bg-white p-5 rounded-xl shadow-md transition-all duration-300 transform hover:scale-[1.01] border border-gray-100">
    
    <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
        <div class="flex-grow">
            <h3 class="text-lg font-bold text-gray-800">#{{ $order->id }} - {{ $order->customer_name }}</h3>
            
            <div class="flex items-center gap-2 mt-1">
                {{-- SLA TIMER (Fitur Baru) --}}
                @php
                    // 1. Ambil waktu sekarang dan waktu pesanan
                    $startTime = $order->created_at;
                    $now = now(); 

                    // 2. Hitung selisih untuk label jam/menit/detik
                    $diff = $startTime->diff($now);
                    $parts = [];
                    if ($diff->h > 0) $parts[] = $diff->h . 'j';
                    if ($diff->i > 0) $parts[] = $diff->i . 'm';
                    $parts[] = $diff->s . 'd';
                    $timeLabel = implode(' ', $parts);

                    // 3. Logika Urgensi (20 menit)
                    $totalMinutes = $startTime->diffInMinutes($now);
                    $isUrgent = ($totalMinutes >= 20 && in_array($order->status, ['pending', 'accepted', 'preparing']));
                @endphp

                <span class="text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wider flex items-center gap-1
                    @if($isUrgent) bg-red-600 text-white animate-pulse @else bg-gray-100 text-gray-500 @endif">
                    <i class="fas fa-hourglass-half text-[8px]"></i> {{ $timeLabel }}
                </span>

                <span class="text-[10px] text-gray-400 font-medium">| {{ $startTime->format('H:i') }} WIB</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            {{-- STATUS BADGE (Logika Asli Kamu) --}}
            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                @if($order->status == 'pending') bg-yellow-100 text-yellow-700 animate-pulse
                @elseif($order->status == 'accepted') bg-blue-100 text-blue-700
                @elseif($order->status == 'ready_for_delivery') bg-green-100 text-green-700
                @elseif($order->status == 'on_delivery') bg-purple-100 text-purple-700
                @elseif($order->status == 'completed' || $order->status == 'delivered') bg-gray-500 text-white
                @endif">
                {{ str_replace('_', ' ', $order->status) }}
            </span>
            {{-- Tombol Panah Accordion --}}
            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>
    </div>

    <div class="mt-4 p-3 bg-red-50/50 rounded-lg border border-dashed border-red-200">
        <ul class="space-y-1">
            @foreach($order->orderItems->take(2) as $item) {{-- Tampilkan 2 item pertama saja untuk ringkasan --}}
                <li class="text-xs text-gray-700 flex justify-between">
                    <span><span class="font-bold">{{ $item->quantity }}x</span> {{ $item->product_name }}</span>
                </li>
            @endforeach
            @if($order->orderItems->count() > 2)
                <li class="text-[10px] text-gray-400 italic">+ {{ $order->order_items->count() - 2 }} item lainnya...</li>
            @endif
        </ul>
    </div>

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
                <p class="text-gray-400 uppercase font-bold text-[9px]">Alamat/Catatan</p>
                <p class="font-semibold text-gray-700">{{ $order->delivery_address ?? 'Ambil di Toko' }}</p>
            </div>
        </div>

        <div class="mt-2">
            <h4 class="text-[10px] font-bold text-gray-400 uppercase mb-1">Detail Item Lengkap:</h4>
            <ul class="bg-gray-50 p-2 rounded-md">
                @foreach($order->orderItems as $item)
                    <li class="text-xs py-1 border-b border-gray-200 last:border-0">
                        <span class="font-bold text-red-600">{{ $item->quantity }}x</span> {{ $item->product_name }}
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
    
    <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-100">
        @switch($order->status)
            @case('pending')
                <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="accepted">
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-lg shadow hover:bg-red-700 transition-colors">
                        Terima Pesanan
                    </button>
                </form>
                @break

            @case('accepted')
                @if($order->order_type === 'delivery')
                    <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="on_delivery">
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs font-bold rounded-lg shadow hover:bg-purple-700 transition-colors">
                            Mulai Antar
                        </button>
                    </form>
                @elseif($order->order_type === 'pickup')
                    <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="ready_for_delivery">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-xs font-bold rounded-lg shadow hover:bg-green-700 transition-colors">
                            Siap Diambil
                        </button>
                    </form>
                @endif
                @break

            @case('ready_for_delivery')
                @if($order->order_type === 'pickup')
                    <a href="{{ route('pegawai.qr.verify.form', ['order_id' => $order->id]) }}" class="px-4 py-2 bg-teal-600 text-white text-xs font-bold rounded-lg shadow hover:bg-teal-700 transition-colors">
                        Verifikasi PIN
                    </a>
                @endif
                @break

            @case('on_delivery')
                <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="delivered">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow hover:bg-indigo-700 transition-colors">
                        Selesaikan Antar
                    </button>
                </form>
                @break

            @default
                <span class="px-4 py-2 text-gray-400 text-xs font-bold italic">Pesanan Selesai</span>
        @endswitch

        <a href="{{ route('pegawai.orders.show', $order->id) }}" 
        class="px-4 py-2 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg hover:bg-gray-200 transition-colors">
            Detail
        </a>
    </div>
</div>