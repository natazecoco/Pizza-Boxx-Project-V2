<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload; // Komponen Upload
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Card; // Card component
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationGroup = 'Katalog Produk';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([ 
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->label('Nama Produk'),
                    
                    Select::make('category_id') 
                        ->relationship('category', 'name') 
                        ->required()
                        ->label('Kategori'),
                    
                    Textarea::make('description') 
                        ->nullable()
                        ->columnSpanFull() 
                        ->label('Deskripsi Produk'),
                    
                    TextInput::make('base_price')
                        ->numeric() 
                        ->required()
                        ->prefix('Rp') 
                        ->label('Harga Dasar'),
                    
                    // --- BAGIAN INI YANG DI-UPGRADE ---
                    FileUpload::make('image_path') 
                        ->image() 
                        ->directory('products') // Folder penyimpanan
                        
                        // 1. IZINKAN WEBP, JPG, PNG SECARA EKSPLISIT
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        
                        // 2. FITUR CROP/EDIT GAMBAR (Penting biar bisa bikin kotak)
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '1:1', // Rasio Kotak
                            '4:3',
                            '16:9',
                        ])
                        
                        // 3. BATASI UKURAN (2MB)
                        ->maxSize(2048)
                        
                        ->nullable()
                        ->label('Gambar Produk (JPG, PNG, WebP)'),
                    // -----------------------------------

                    Toggle::make('is_available') 
                        ->required()
                        ->default(true)
                        ->label('Tersedia'),
                ])->columns(2), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path') 
                    ->square() 
                    ->label('Gambar'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Produk'),
                TextColumn::make('category.name') 
                    ->searchable()
                    ->sortable()
                    ->label('Kategori'),
                TextColumn::make('base_price')
                    ->money('IDR') 
                    ->sortable()
                    ->label('Harga Dasar'),
                ToggleColumn::make('is_available') 
                    ->label('Tersedia'),
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
                Tables\Filters\SelectFilter::make('category_id') 
                    ->relationship('category', 'name')
                    ->label('Filter Kategori'),
                Tables\Filters\TernaryFilter::make('is_available') 
                    ->label('Ketersediaan')
                    ->boolean() 
                    ->trueLabel('Tersedia')
                    ->falseLabel('Tidak Tersedia') 
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }
}