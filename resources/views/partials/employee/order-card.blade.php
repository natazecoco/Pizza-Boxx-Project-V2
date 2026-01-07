<div x-data="{ open: false }" class="bg-white p-6 rounded-xl shadow-md transition-all duration-300 transform hover:scale-[1.01]">
    <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
        <div class="flex-grow">
            <h3 class="text-lg font-bold text-gray-800">Pesanan #{{ $order->id }} - {{ $order->customer_name }}</h3>
            <p class="text-sm text-gray-500">Masuk pada: {{ $order->created_at->format('d M Y H:i') }} WIB</p>
        </div>
        <div class="flex-shrink-0 flex items-center gap-4">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold 
                @if($order->status == 'pending') bg-yellow-100 text-yellow-700 animate-pulse
                @elseif($order->status == 'accepted') bg-blue-100 text-blue-700
                @elseif($order->status == 'ready_for_pickup') bg-green-100 text-green-700
                @elseif($order->status == 'on_delivery') bg-purple-100 text-purple-700
                @elseif($order->status == 'completed' || $order->status == 'delivered') bg-gray-500 text-white
                @endif
            ">{{ $order->user_friendly_status }}</span>
            <button class="text-gray-400 hover:text-gray-600 transition-colors" :class="{ 'rotate-180': open }">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
        </div>
    </div>
    
    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-y-0" x-transition:enter-end="opacity-100 scale-y-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-y-100" x-transition:leave-end="opacity-0 scale-y-0" class="mt-4 pt-4 border-t border-gray-200 space-y-3">
        <p class="text-gray-600"><strong>Tipe Pesanan:</strong> {{ $order->order_type }}</p>
        <p class="text-gray-600"><strong>Alamat:</strong> {{ $order->delivery_address }}</p>
        <div class="mt-2">
            <h4 class="text-md font-semibold text-gray-700">Item Pesanan:</h4>
            <ul>
                @foreach($order->orderItems as $item)
                    <li>{{ $item->product_name }} (Rp{{ number_format($item->unit_price) }})</li>
                @endforeach
            </ul>
        </div>
        <div class="flex justify-end gap-2 mt-4">
            @switch($order->status)
                @case('pending')
                    <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white font-bold rounded-lg shadow hover:bg-red-700 transition-colors">
                            Terima Pesanan
                        </button>
                    </form>
                    @break
                @case('accepted')
                    @if($order->order_type === 'delivery')
                        <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="on_delivery">
                            <button type="submit" class="px-4 py-2 bg-purple-600 text-white font-bold rounded-lg shadow hover:bg-purple-700 transition-colors">
                                Mulai Pengantaran
                            </button>
                        </form>
                    @elseif($order->order_type === 'pickup')
                        <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="ready_for_pickup">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white font-bold rounded-lg shadow hover:bg-green-700 transition-colors">
                                Siap Diambil
                            </button>
                        </form>
                    @endif
                    @break
                @case('ready_for_pickup')
                    <a href="{{ route('pegawai.qr.verify.form', ['order_id' => $order->id]) }}" class="px-4 py-2 bg-teal-600 text-white font-bold rounded-lg shadow hover:bg-teal-700 transition-colors">
                        Verifikasi Pengambilan
                    </a>
                    @break
                @case('on_delivery')
                    <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="delivered">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-lg shadow hover:bg-indigo-700 transition-colors">
                            Selesaikan Pengantaran
                        </button>
                    </form>
                    @break
                @default
                    <span class="px-4 py-2 text-gray-500 font-semibold">Pesanan Selesai</span>
                    @break
            @endswitch
        </div>
    </div>
</div>