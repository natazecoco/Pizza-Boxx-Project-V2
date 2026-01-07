@extends('layouts.customer')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8 max-w-7xl">
    <div class="mb-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-red-600 mb-2">Dashboard Pelanggan</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">Riwayat lengkap semua pesanan Anda di Pizza Boxx</p>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-orange-500 px-6 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <h2 class="text-xl font-semibold text-white">Riwayat Pesanan</h2>
                <div class="mt-2 md:mt-0">
                    <span class="inline-block bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium">
                        Total Pesanan: {{ $orders->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kode Pesanan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        {{-- Mengganti kolom 'QR Code' menjadi 'PIN' --}}
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">PIN</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-red-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-md font-mono text-sm">
                                {{ $order->order_code ?? $order->id }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-base font-bold text-red-600">
                            Rp {{ number_format($order->total ?? $order->total_amount, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
                                    'on_delivery' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-truck'],
                                    'delivered' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle'],
                                    'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle'],
                                    'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-times-circle'],
                                    'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-ban'],
                                ];
                                $status = strtolower($order->status);
                                $statusConfig = $statusColors[$status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'fa-question-circle'];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} inline-flex items-center">
                                <i class="fas {{ $statusConfig['icon'] }} mr-1"></i>
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        {{-- Menampilkan PIN jika ada --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(($order->order_type === 'takeaway' || $order->order_type === 'pickup') && $order->pin)
                                <span class="inline-block px-3 py-1.5 text-lg font-bold text-white bg-red-600 rounded-md shadow-sm">
                                    {{ $order->pin }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('checkout.success', ['order_id' => $order->id]) }}"
                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                <i class="fas fa-info-circle mr-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-shopping-bag text-4xl mb-3"></i>
                                <p class="text-lg">Belum ada pesanan</p>
                                <a href="{{ route('menu.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <i class="fas fa-utensils mr-2"></i> Pesan Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    /* Animation for table rows */
    tr {
        animation: fadeIn 0.3s ease-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Staggered animation */
    tr:nth-child(1) { animation-delay: 0.1s; }
    tr:nth-child(2) { animation-delay: 0.2s; }
    tr:nth-child(3) { animation-delay: 0.3s; }
    tr:nth-child(4) { animation-delay: 0.4s; }
    tr:nth-child(5) { animation-delay: 0.5s; }
</style>
@endsection