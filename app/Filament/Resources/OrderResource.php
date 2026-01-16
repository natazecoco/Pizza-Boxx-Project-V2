<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\User;
use App\Models\Location;
use App\Models\Promo;
use App\Models\Product;
use App\Models\OrderItem; // Pastikan ini ada
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Filters\SelectFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    // *** Metode ini akan mengelola perhitungan total secara terpusat ***
    public static function updateTotals(Get $get, Set $set): void
    {
        $orderItems = $get('orderItems');
        $subtotal = 0;

        if (is_array($orderItems)) {
            foreach ($orderItems as $item) {
                // Pastikan item produk sudah dipilih dan quantity/unit_price ada
                $subtotal += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            }
        }

        $discountAmount = 0;
        $promoId = $get('promo_id');
        if ($promoId) {
            $promo = Promo::find($promoId);
            if ($promo && ($subtotal >= ($promo->min_order_amount ?? 0))) {
                if ($promo->type === 'percentage') {
                    $discountAmount = $subtotal * ($promo->value / 100);
                } elseif ($promo->type === 'fixed_amount') {
                    $discountAmount = $promo->value;
                }
            }
        }
        $set('discount_amount', round($discountAmount, 2));

        $deliveryFee = $get('delivery_fee');
        $total = $subtotal - $discountAmount + $deliveryFee;

        $set('subtotal_amount', round($subtotal, 2));
        $set('total_amount', round($total, 2));
    }
    // *** Akhir dari metode perhitungan ***

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Informasi Pelanggan & Pesanan')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    // Field user_id untuk mengisi data pelanggan secara otomatis
                                    Select::make('user_id')
                                        ->relationship('user', 'name')
                                        ->placeholder('Pilih User (Opsional)')
                                        ->label('User Pelanggan')
                                        ->live()
                                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                                            $user = User::find($state);
                                            if ($user) {
                                                $set('customer_name', $user->name);
                                                $set('customer_email', $user->email);
                                                $set('customer_phone', $user->phone); // Asumsi ada kolom phone di tabel users
                                            }
                                        }),
                                    TextInput::make('customer_name')
                                        ->required()
                                        ->maxLength(255)
                                        ->label('Nama Pelanggan'),
                                    TextInput::make('customer_phone')
                                        ->tel()
                                        ->required()
                                        ->maxLength(20)
                                        ->label('Telepon Pelanggan'),
                                    TextInput::make('customer_email')
                                        ->email()
                                        ->nullable()
                                        ->maxLength(255)
                                        ->label('Email Pelanggan'),
                                    Select::make('location_id')
                                        ->relationship('location', 'name')
                                        ->required()
                                        ->label('Lokasi Toko')
                                        ->live(),
                                ]),
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Select::make('order_type')
                                        ->options([
                                            'delivery' => 'Delivery',
                                            'pickup' => 'Pickup',
                                        ])
                                        ->required()
                                        ->native(false)
                                        ->live()
                                        ->label('Tipe Pesanan'),
                                    Select::make('payment_method')
                                        ->options([
                                            'online' => 'Online Payment',
                                            'cash_on_delivery' => 'Cash on Delivery (COD)',
                                            'card_on_pickup' => 'Card on Pickup',
                                        ])
                                        ->required()
                                        ->native(false)
                                        ->live()
                                        ->label('Metode Pembayaran'),
                                ]),
                            Textarea::make('delivery_address')
                                ->nullable()
                                ->columnSpanFull()
                                ->label('Alamat Pengiriman (Jika Delivery)'),
                            Textarea::make('delivery_notes')
                                ->nullable()
                                ->columnSpanFull()
                                ->label('Catatan Pengiriman'),
                        ]),
                    Step::make('Detail Item Pesanan')
                        ->schema([
                            Repeater::make('orderItems')
                                ->relationship('orderItems')
                                ->schema([
                                    Select::make('product_id')
                                        ->relationship('product', 'name')
                                        ->required()
                                        ->label('Produk')
                                        ->live()
                                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('unit_price', $product->base_price);
                                                $set('product_name', $product->name);
                                            }
                                        }),
                                    TextInput::make('product_name')
                                        ->required()
                                        ->readOnly()
                                        ->label('Nama Produk (Otomatis)'),
                                    TextInput::make('quantity')
                                        ->numeric()
                                        ->required()
                                        ->default(1)
                                        ->minValue(1)
                                        ->live()
                                        ->label('Jumlah'),
                                    TextInput::make('unit_price')
                                        ->numeric()
                                        ->required()
                                        ->prefix('Rp')
                                        ->label('Harga Satuan')
                                        ->readOnly(),
                                ])
                                ->collapsible()
                                ->defaultItems(1)
                                ->columns(4) // Ubah menjadi 4 kolom karena ada 4 field
                                ->addActionLabel('Tambah Item Pesanan')
                                ->live() // Perbarui total saat item ditambah/dihapus
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    static::updateTotals($get, $set);
                                }),
                        ]),
                    Step::make('Ringkasan & Status')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    TextInput::make('subtotal_amount')
                                        ->numeric()
                                        ->required()
                                        ->prefix('Rp')
                                        ->readOnly()
                                        ->label('Subtotal'),
                                    TextInput::make('discount_amount')
                                        ->numeric()
                                        ->required()
                                        ->prefix('Rp')
                                        ->default(0.00)
                                        ->readOnly()
                                        ->label('Diskon'),
                                    TextInput::make('delivery_fee')
                                        ->numeric()
                                        ->required()
                                        ->prefix('Rp')
                                        ->default(0.00)
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            static::updateTotals($get, $set);
                                        })
                                        ->label('Biaya Pengiriman'),
                                    TextInput::make('total_amount')
                                        ->numeric()
                                        ->required()
                                        ->prefix('Rp')
                                        ->readOnly()
                                        ->label('Total Pembayaran'),
                                    Select::make('promo_id')
                                        ->relationship('promo', 'name')
                                        ->placeholder('Pilih Promo (Opsional)')
                                        ->nullable()
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            static::updateTotals($get, $set);
                                        })
                                        ->label('Promo Digunakan'),
                                ]),
                            Select::make('status')
                                ->options([
                                    'pending' => 'Pending (Menunggu Konfirmasi)',
                                    'accepted' => 'Accepted (Diterima Toko)',
                                    'preparing' => 'Preparing (Sedang Disiapkan)',
                                    'ready_for_delivery' => 'Ready for Delivery/Pickup (Siap Diantar/Diambil)',
                                    'on_delivery' => 'On Delivery (Dalam Pengantaran)',
                                    'delivered' => 'Delivered (Sudah Diantar)',
                                    'completed' => 'Completed (Selesai)',
                                    'cancelled' => 'Cancelled (Dibatalkan)',
                                    'refunded' => 'Refunded (Dikembalikan)',
                                ])
                                ->required()
                                ->native(false)
                                ->label('Status Pesanan'),
                            Select::make('delivery_employee_id')
                                ->relationship('deliveryEmployee', 'name', fn (Builder $query) => $query->where('role', 'employee'))
                                ->nullable()
                                ->label('Pegawai Pengantar (Opsional)'),
                            DatePicker::make('delivered_at')
                                ->nullable()
                                ->native(false)
                                ->label('Waktu Selesai/Diantar'),
                        ]),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID Pesanan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable()
                    ->label('Pelanggan'),
                TextColumn::make('location.name')
                    ->searchable()
                    ->sortable()
                    ->label('Toko'),
                TextColumn::make('order_type')
                    ->label('Tipe')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'accepted' => 'info',
                        'preparing' => 'warning',
                        'ready_for_delivery' => 'sky',
                        'on_delivery' => 'primary',
                        'delivered' => 'success',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'refunded' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('total_amount')
                    ->money('IDR')
                    ->sortable()
                    ->label('Total'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Dibuat'),
                TextColumn::make('deliveryEmployee.name')
                    ->label('Pegawai Pengantar'),
                TextColumn::make('delivered_at')
                    ->dateTime()
                    ->label('Waktu Selesai'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'preparing' => 'Preparing',
                        'ready_for_delivery' => 'Ready for Delivery',
                        'on_delivery' => 'On Delivery',
                        'delivered' => 'Delivered',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ])
                    ->label('Filter Status'),
                SelectFilter::make('order_type')
                    ->options([
                        'delivery' => 'Delivery',
                        'pickup' => 'Pickup',
                    ])
                    ->label('Filter Tipe'),
                SelectFilter::make('location_id')
                    ->relationship('location', 'name')
                    ->label('Filter Lokasi'),
                SelectFilter::make('delivery_employee_id')
                    ->relationship('deliveryEmployee', 'name', fn (Builder $query) => $query->where('role', 'employee'))
                    ->label('Filter Pegawai'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // Ekspor ke Excel menggunakan paket filament-excel
                    ExportBulkAction::make()
                    ->label('Ekspor ke Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}