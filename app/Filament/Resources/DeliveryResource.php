<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryResource\Pages;
use App\Models\Delivery;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck'; // Ikon truk lebih pas
    protected static ?string $navigationGroup = 'Logistik'; // Grup baru biar rapi
    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        // Untuk sekarang, biarkan hanya Super Admin yang bisa akses modul logistik ini
        return auth('employee')->user()?->isSuperAdmin() ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->label('ID Pesanan')
                    ->searchable()
                    ->required(),

                Select::make('delivery_employee_id')
                    ->relationship('deliveryEmployee', 'name', fn (Builder $query) => $query->where('role', 'employee'))
                    ->label('Kurir')
                    ->required(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'on_delivery' => 'Dalam Pengantaran',
                        'delivered' => 'Sampai Tujuan',
                        'failed' => 'Gagal Antar',
                    ])
                    ->native(false)
                    ->required(),

                DateTimePicker::make('assigned_at')->label('Waktu Penugasan'),
                DateTimePicker::make('picked_up_at')->label('Waktu Diambil Kurir'),
                DateTimePicker::make('delivered_at')->label('Waktu Sampai'),
                
                Textarea::make('notes')
                    ->label('Catatan Kurir')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.id')->label('Order #')->sortable(),
                TextColumn::make('deliveryEmployee.name')->label('Kurir')->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'on_delivery' => 'info',
                        'delivered' => 'success',
                        'failed' => 'danger',
                    }),
                TextColumn::make('assigned_at')->dateTime()->label('Ditugaskan')->sortable(),
                TextColumn::make('delivered_at')->dateTime()->label('Sampai')->sortable(),
            ])
            ->filters([
                // Tambahkan filter status jika perlu
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeliveries::route('/'),
            'create' => Pages\CreateDelivery::route('/create'),
            'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }
}