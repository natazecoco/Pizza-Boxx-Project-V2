@extends('layouts.employee')

@section('content')
<div class="container mx-auto py-8 px-4 max-w-4xl">
    <div class="flex justify-between items-center mb-6 no-print">
        <a href="{{ route('pegawai.orders.index') }}" class="text-gray-600 hover:text-brand-red font-bold flex items-center gap-2 transition-colors">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <div class="flex gap-3">
            <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 hover:bg-black transition-all shadow-md">
                <i class="fas fa-print"></i> Cetak Invoice
            </button>
            @php
                // 1. Ambil nomor telepon asli
                $rawPhone = $order->customer_phone;

                // 2. Bersihkan karakter selain angka (spasi, strip, dll)
                $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);

                // 3. Logika ganti angka 0 di depan jadi 62
                if (str_starts_with($cleanPhone, '0')) {
                    $formattedPhone = '62' . substr($cleanPhone, 1);
                } elseif (str_starts_with($cleanPhone, '8')) {
                    $formattedPhone = '62' . $cleanPhone;
                } else {
                    $formattedPhone = $cleanPhone;
                }

                // 4. Siapkan pesan otomatis (Logic punya kamu)
                $statusPesan = match($order->status) {
                    'ready_for_pickup'   => 'sudah siap diambil di toko!',
                    'ready_for_delivery' => 'sudah siap dan akan segera diantar oleh kurir kami!',
                    'on_delivery'        => 'sedang dalam perjalanan menuju lokasi Kakak!',
                    default              => 'sedang kami proses.',
                };

                $waMessage = urlencode("Halo Kak " . $order->customer_name . ", kami dari Pizza Boxx ingin menginfokan bahwa pesanan #" . $order->id . " " . $statusPesan);
            @endphp

            <a href="https://wa.me/{{ $formattedPhone }}?text={{ $waMessage }}" 
            target="_blank" 
            class="bg-green-500 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 shadow-md hover:bg-green-600 transition-all">
                <i class="fab fa-whatsapp"></i> Chat Pelanggan
            </a>
        </div>
    </div>

    <div id="printable-area" class="bg-white shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-brand-red p-8 text-white flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black italic tracking-tighter">PIZZA BOXX</h1>
                <p class="text-sm opacity-80 uppercase tracking-widest mt-1">Order Invoice</p>
            </div>
            <div class="text-right">
                <p class="text-lg font-bold">#{{ $order->id }}</p>
                <p class="text-xs opacity-80">{{ $order->created_at->format('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Pelanggan</h3>
                    <div class="space-y-2">
                        <p class="text-lg font-bold text-gray-800">{{ $order->customer_name }}</p>
                        <p class="text-gray-600"><i class="fas fa-phone-alt mr-2 text-red-500"></i> {{ $order->customer_phone }}</p>
                        <p class="text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i> 
                            {{ $order->order_type === 'pickup' ? 'Ambil di Toko (Self-Pickup)' : ($order->delivery_address ?? '-') }}
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-500">Tipe Pesanan:</span>
                        <span class="text-sm font-bold uppercase text-brand-red">{{ $order->order_type }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Status Saat Ini:</span>
                        <span class="text-sm font-bold uppercase text-blue-600">{{ str_replace('_', ' ', $order->status) }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b-2 border-gray-100">
                            <th class="py-4 text-sm font-bold text-gray-400 uppercase tracking-wider">Menu</th>
                            <th class="py-4 text-sm font-bold text-gray-400 uppercase tracking-wider text-center">Qty</th>
                            <th class="py-4 text-sm font-bold text-gray-400 uppercase tracking-wider text-right">Harga</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($order->orderItems as $item)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            {{-- Tambah Checkbox untuk menandai pizza yang sudah dibuat --}}
                            <td class="py-5 no-print">
                                <input type="checkbox" class="w-6 h-6 rounded border-gray-300 text-green-600 focus:ring-green-500 cursor-pointer">
                            </td>
                            <td class="py-5">
                                <p class="font-black text-gray-800 text-lg uppercase leading-none">{{ $item->product_name }}</p>
                                @if($item->options)
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @php
                                            $options = is_array($item->options) ? $item->options : explode(',', $item->options);
                                        @endphp
                                        @foreach($options as $opt)
                                            <span class="text-[10px] bg-yellow-100 text-orange-800 px-2 py-0.5 rounded-md font-black border border-orange-200 uppercase">
                                                {{-- INI PERBAIKANNYA --}}
                                                {{-- Cek jika $opt adalah array (format baru) atau string (format lama) --}}
                                                {{ is_array($opt) ? ($opt['name'] ?? '-') : trim($opt) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="py-5 text-center font-black text-xl text-gray-800">{{ $item->quantity }}</td>
                            <td class="py-5 text-right font-bold text-gray-800">Rp{{ number_format($item->unit_price * $item->quantity) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-200">
                            <td colspan="2" class="py-6 text-right font-bold text-gray-500">TOTAL PEMBAYARAN:</td>
                            <td class="py-6 text-right text-2xl font-black text-brand-red">Rp{{ number_format($order->total_amount) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    {{-- BAGIAN BAWAH KARTU OPERASIONAL (Update Status) --}}
    <div class="bg-white rounded-2xl shadow-lg p-8 mt-8 border-l-4 border-blue-500 no-print">
        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-edit text-blue-600"></i> Perbarui Operasional Pesanan
        </h3>

        @php
            $nextAction = match($order->status) {
                'pending'   => ['status' => 'accepted', 'label' => 'TERIMA & PROSES', 'color' => 'bg-blue-600', 'icon' => 'fa-check'],
                'accepted'  => ['status' => 'preparing', 'label' => 'MULAI MASAK', 'color' => 'bg-orange-500', 'icon' => 'fa-fire'],
                'preparing' => [
                    'status' => ($order->order_type === 'pickup' ? 'ready_for_pickup' : 'ready_for_delivery'),
                    'label'  => 'PIZZA MATANG & SIAP',
                    'color'  => 'bg-green-600',
                    'icon'   => 'fa-pizza-slice'
                ],
                'ready_for_pickup' => [
                    'url'    => route('pegawai.qr.verify.form', ['order_id' => $order->id]),
                    'label'  => 'VERIFIKASI PIN PELANGGAN',
                    'color'  => 'bg-teal-600',
                    'icon'   => 'fa-key'
                ],
                'on_delivery' => ['status' => 'delivered', 'label' => 'KONFIRMASI SAMPAI', 'color' => 'bg-indigo-600', 'icon' => 'fa-map-marker-alt'],
                default => null
            };
        @endphp

        @if($nextAction)
        <div class="mb-4 no-print">
            @if(isset($nextAction['url']))
                <a href="{{ $nextAction['url'] }}" class="w-full {{ $nextAction['color'] }} text-white py-6 rounded-2xl font-black text-2xl shadow-xl hover:scale-[1.01] transition-all flex items-center justify-center gap-4">
                    <i class="fas {{ $nextAction['icon'] }} animate-bounce"></i>
                    {{ $nextAction['label'] }}
                </a>
            @else
                <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="{{ $nextAction['status'] }}">
                    <button type="submit" class="w-full {{ $nextAction['color'] }} text-white py-6 rounded-2xl font-black text-2xl shadow-xl hover:scale-[1.01] transition-all flex items-center justify-center gap-4">
                        <i class="fas {{ $nextAction['icon'] }} animate-bounce"></i>
                        {{ $nextAction['label'] }}
                    </button>
                </form>
            @endif
        </div>
        @endif

        <form action="{{ route('pegawai.orders.update-status', $order->id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div class="md:col-span-2">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Pilih Status Baru</label>
                    <select name="status" id="status" class="w-full rounded-xl border-gray-300 p-3 bg-gray-50">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>Diterima (Accepted)</option>
                        <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Sedang Dimasak (Preparing)</option>
                        
                        @if($order->order_type === 'delivery')
                            <option value="ready_for_delivery" {{ $order->status == 'ready_for_delivery' ? 'selected' : '' }}>Siap Diantar</option>
                            <option value="on_delivery" {{ $order->status == 'on_delivery' ? 'selected' : '' }}>Sedang Diantar</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Sudah Sampai (Delivered)</option>
                        @else
                            <option value="ready_for_pickup" {{ $order->status == 'ready_for_pickup' ? 'selected' : '' }}>Siap Diambil</option>
                        @endif

                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Batalkan Pesanan</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-md flex items-center justify-center gap-2">
                    <i class="fas fa-sync-alt"></i> Update Status
                </button>
            </div>
        </form>
    </div>

    {{-- RIWAYAT STATUS --}}
    <div class="bg-white rounded-2xl shadow-lg p-8 mt-8 border border-gray-100 no-print">
        <h3 class="text-lg font-bold text-gray-800 mb-8 flex items-center gap-2">
            <i class="fas fa-history text-brand-red"></i> Riwayat Status Pesanan
        </h3>

        <div class="relative">
            <div class="absolute left-3 top-0 h-full w-0.5 bg-gray-100"></div>

            <div class="relative pl-10 mb-8">
                <span class="absolute left-0 top-1 bg-brand-red w-6 h-6 rounded-full border-4 border-white shadow-sm flex items-center justify-center">
                    <i class="fas fa-check text-[10px] text-white"></i>
                </span>
                <p class="text-sm font-bold text-gray-800">Pesanan Diterima</p>
                <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
            </div>

            @if($order->status != 'pending' && $order->status != 'accepted')
            <div class="relative pl-10 mb-8">
                <span class="absolute left-0 top-1 bg-blue-500 w-6 h-6 rounded-full border-4 border-white shadow-sm flex items-center justify-center">
                    <i class="fas fa-pizza-slice text-[10px] text-white"></i>
                </span>
                <p class="text-sm font-bold text-gray-800">
                    @if($order->status == 'ready_for_pickup')
                        Pizza Siap Diambil
                    @elseif($order->status == 'ready_for_delivery')
                        Pizza Siap Diantar
                    @else
                        Sedang Disiapkan/Dikirim
                    @endif
                </p>
                <p class="text-xs text-gray-400">Terakhir diperbarui: {{ $order->updated_at->format('d M Y, H:i') }} WIB</p>
            </div>
            @endif

            @if($order->status == 'completed' || $order->status == 'delivered')
            <div class="relative pl-10">
                <span class="absolute left-0 top-1 bg-green-500 w-6 h-6 rounded-full border-4 border-white shadow-sm flex items-center justify-center">
                    <i class="fas fa-flag-checkered text-[10px] text-white"></i>
                </span>
                <p class="text-sm font-bold text-gray-800">Pesanan Selesai / Tiba</p>
                <p class="text-xs text-gray-400">Telah diterima oleh pelanggan atau sampai di lokasi.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        /* Sembunyikan SEMUA elemen di layar */
        body * { visibility: hidden; }
        
        /* Hanya tampilkan area invoice dan isinya */
        #printable-area, #printable-area * { visibility: visible; }
        
        /* Posisikan area yang diprint ke pojok kiri atas */
        #printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
        }

        /* Hilangkan elemen yang tidak perlu di dalam struk */
        .no-print { display: none !important; }
    }
</style>
@endsection