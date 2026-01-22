<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection;
use Filament\Tables\Filters\SelectFilter; 

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    // Kustomisasi query Eloquent untuk menyesuaikan visibilitas data
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth('employee')->user();

        // Safety check: Pastikan user ada
        if (!$user) return $query;

        if ($user->isBranchManager()) {
            // Manager hanya lihat user di lokasinya sendiri
            // Dan biasanya tidak boleh lihat Super Admin
            return $query->where('location_id', $user->location_id)
                        ->where('role', '!=', 'super_admin');
        }

        return $query;
    }    

    // Formulir untuk membuat dan mengedit pengguna
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->label('Email'),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string => bcrypt($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->label('Password'),
                Select::make('location_id')
                    ->relationship('location', 'name') // Mengambil data dari relasi 'location' di model User
                    ->label('Lokasi Cabang')
                    ->placeholder('Pilih Cabang')
                    ->searchable()
                    ->preload()
                    ->required(fn (Get $get) => $get('role') !== 'super_admin')
                    ->default(fn () => auth('employee')->user()->location_id)
                    // Kunci field jika yang login adalah Manager Cabang
                    ->disabled(fn () => auth('employee')->user()->isBranchManager())
                    ->dehydrated()
                    ->nullable(), // Izinkan kosong jika user adalah Admin Pusat
                Select::make('role') // Kita arahkan ke kolom 'role' di database
                    ->options(function () {
                        $user = auth('employee')->user();
                        if ($user->isSuperAdmin()) {
                            return [
                                'super_admin' => 'Super Admin',
                                'branch_manager' => 'Branch Manager',
                                'employee' => 'Employee',
                                'customer' => 'Customer',
                            ];
                        }
                        // Manager Cabang tidak boleh bikin Super Admin baru
                        return [
                            'employee' => 'Employee',
                            'customer' => 'Customer',
                        ];
                    })
                    ->required()
                    ->native(false)
                    ->label('Peran')
                    ->placeholder('Pilih Peran')
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        // Ini triknya: Saat role dipilih, kita siapkan data 
                        // untuk sinkronisasi Spatie nantinya
                        $set('roles', [$state]); 
                }),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    // ->multiple()
                    ->placeholder('Pilih Peran')
                    ->hidden() // Sembunyikan field ini
                    ->preload()
                    ->required(false)
                    ->label('Peran')
                    ->dehydrated(true),
            ]);
    }

    // Tabel untuk menampilkan daftar pengguna
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama'),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label('Email'),
                TextColumn::make('roles.name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->label('Peran'),
                TextColumn::make('location.name')
                    ->searchable()
                    ->sortable()
                    ->default('N/A')
                    ->label('Lokasi Cabang'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dibuat Pada'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Diperbarui Pada'),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Filter Peran'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(function (User $record): bool {
                        $user = auth('employee')->user();
                        
                        // Tidak bisa hapus diri sendiri
                        if ($record->id === $user->id) return true;
                        
                        // Manager Cabang tidak bisa hapus akun dengan role tertentu jika perlu
                        if ($user->isBranchManager() && $record->isSuperAdmin()) return true;

                        return false;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->hidden(function (?Collection $records): bool {
                            if (is_null($records) || $records->isEmpty()) {
                                return false;
                            }
                            // Perbaikan: Menentukan guard 'employee'
                            $adminRoles = $records->filter(fn (User $user) => $user->isSuperAdmin());
                            return $adminRoles->count() > 0 && User::where('role', 'super_admin')->count() <= $adminRoles->count();
                        }),
                ]),
            ]);
    }

    // Relasi (jika ada)
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // Halaman untuk resource ini
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}