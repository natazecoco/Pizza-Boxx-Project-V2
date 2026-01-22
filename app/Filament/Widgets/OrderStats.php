<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat; // Gunakan Stat, bukan Card

class OrderStats extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $user = auth('employee')->user();
        
        // 1. Inisialisasi query dasar
        $query = Order::query();

        // 2. Filter Lokasi (Scoping)
        if ($user?->isBranchManager()) {
            $query->where('location_id', $user->location_id);
        }

        return [
            // Kartu 1: Total Semua Pesanan
            Stat::make('Total Pesanan', (clone $query)->count())
                ->icon('heroicon-m-shopping-cart')
                ->description('Semua status pesanan')
                ->descriptionIcon('heroicon-m-list-bullet')
                ->color('primary'),

            // Kartu 2: Pesanan Selesai
            Stat::make('Pesanan Selesai', (clone $query)->where('status', 'completed')->count())
                ->icon('heroicon-m-check-circle')
                ->description('Berhasil dikirim/diambil')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            // Kartu 3: Total Pendapatan
            Stat::make('Pendapatan Total', 'Rp ' . number_format((clone $query)->whereIn('status', ['completed', 'delivered'])->sum('total_amount'), 0, ',', '.'))
                ->icon('heroicon-m-currency-dollar')
                ->description('Total omzet cabang')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'), // Merah khas Pizza Boxx
        ];
    }
}