<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Location;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderPerLocationStats extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = [];

        // 1. Ambil semua lokasi/cabang yang ada
        $locations = Location::all();

        foreach ($locations as $location) {
            // 2. Hitung jumlah pesanan per lokasi
            $orderCount = Order::where('location_id', $location->id)->count();
            
            // 3. Hitung total uang masuk per lokasi (Opsional tapi keren)
            $totalRevenue = Order::where('location_id', $location->id)
                ->where('status', 'completed') // Hanya yang sudah selesai
                ->sum('total_amount');

            $stats[] = Stat::make("Pesanan di " . $location->name, $orderCount . " Pesanan")
                ->description('Total Pendapatan: Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]); // Ini grafik hiasan, nanti bisa dibuat dinamis
        }

        return $stats;
    }
}