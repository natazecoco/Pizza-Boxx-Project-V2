@extends('layouts.employee')

@section('content')
<div class="container mx-auto py-8 px-4 max-w-4xl">
    <div class="flex justify-between items-center mb-6 no-print">
        <a href="{{ route('pegawai.orders.index') }}" class="text-gray-600 hover:text-red-600 font-bold flex items-center gap-2 transition-colors">
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

                // 4. Siapkan pesan otomatis (opsional)
                $waMessage = urlencode("Halo Kak " . $order->customer_name . ", kami dari Pizza Boxx ingin menginfokan bahwa pesanan #" . $order->id . " " . ($order->status == 'ready_for_delivery' ? 'sudah siap diambil!' : 'sedang dalam proses.'));
            @endphp

            <a href="https://wa.me/{{ $formattedPhone }}?text={{ $waMessage }}" 
            target="_blank" 
            class="bg-green-500 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 shadow-md hover:bg-green-600 transition-all">
                <i class="fab fa-whatsapp"></i> Chat Pelanggan
            </a>
            <!-- <a href="https://wa.me/{{ str_replace('0', '62', $order->customer_phone) }}" target="_blank" class="bg-green-500 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 shadow-md">
                <i class="fab fa-whatsapp"></i> Chat Pelanggan
            </a> -->
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-red-600 p-8 text-white flex justify-between items-center">
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
                        <span class="text-sm font-bold uppercase text-red-600">{{ $order->order_type }}</span>
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
                        <tr>
                            <td class="py-5">
                                <p class="font-bold text-gray-800">{{ $item->product_name }}</p>
                                @if($item->options)
                                    <p class="text-xs text-gray-500 italic">{{ is_array($item->options) ? implode(', ', $item->options) : $item->options }}</p>
                                @endif
                            </td>
                            <td class="py-5 text-center font-bold text-gray-800">{{ $item->quantity }}</td>
                            <td class="py-5 text-right font-bold text-gray-800">Rp{{ number_format($item->unit_price * $item->quantity) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-200">
                            <td colspan="2" class="py-6 text-right font-bold text-gray-500">TOTAL PEMBAYARAN:</td>
                            <td class="py-6 text-right text-2xl font-black text-red-600">Rp{{ number_format($order->total_amount) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8 mt-8 border border-gray-100 no-print">
        <h3 class="text-lg font-bold text-gray-800 mb-8 flex items-center gap-2">
            <i class="fas fa-history text-red-600"></i> Riwayat Status Pesanan
        </h3>

        <div class="relative">
            <div class="absolute left-3 top-0 h-full w-0.5 bg-gray-100"></div>

            <div class="relative pl-10 mb-8">
                <span class="absolute left-0 top-1 bg-red-600 w-6 h-6 rounded-full border-4 border-white shadow-sm flex items-center justify-center">
                    <i class="fas fa-check text-[10px] text-white"></i>
                </span>
                <p class="text-sm font-bold text-gray-800">Pesanan Diterima</p>
                <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
            </div>

            @if($order->status != 'pending')
            <div class="relative pl-10 mb-8">
                <span class="absolute left-0 top-1 bg-blue-500 w-6 h-6 rounded-full border-4 border-white shadow-sm flex items-center justify-center">
                    <i class="fas fa-pizza-slice text-[10px] text-white"></i>
                </span>
                <p class="text-sm font-bold text-gray-800">Sedang Disiapkan/Dikirim</p>
                <p class="text-xs text-gray-400">{{ $order->updated_at->format('d M Y, H:i') }} WIB</p>
            </div>
            @endif

            @if($order->status == 'completed' || $order->status == 'delivered')
            <div class="relative pl-10">
                <span class="absolute left-0 top-1 bg-green-500 w-6 h-6 rounded-full border-4 border-white shadow-sm flex items-center justify-center">
                    <i class="fas fa-flag-checkered text-[10px] text-white"></i>
                </span>
                <p class="text-sm font-bold text-gray-800">Pesanan Selesai</p>
                <p class="text-xs text-gray-400">Telah diterima oleh pelanggan.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
@media print {
    .no-print, .sidebar, nav, button { display: none !important; }
    body { background: white !important; margin: 0; padding: 0; }
    .md\:ml-64 { margin-left: 0 !important; }
    .container { max-width: 100% !important; width: 100% !important; box-shadow: none !important; }
    .bg-white { box-shadow: none !important; border: none !important; }
    @page { margin: 1.5cm; }
}
</style>
@endsection