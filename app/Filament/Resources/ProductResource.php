<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\PhotosRelationManager;
use App\Models\Brand;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box'; // Changed icon

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('about')
                    ->required()
                    ->maxLength(1024),

                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('IDR'),

                Forms\Components\FileUpload::make('thumbnail')
                    ->required()
                    ->image()
                    ->optimize('webp'),

                Forms\Components\Repeater::make('productSizes')
                    ->relationship()
                    ->schema([
                        Forms\Components\TextInput::make('size')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('stock')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                    ])
                    ->columns(2)
                    ->defaultItems(1), // Start with one size field by default

                Forms\Components\Repeater::make('photos')
                    ->relationship()
                    ->schema([
                        Forms\Components\FileUpload::make('photo')
                            ->required()
                            ->image()
                            ->optimize('webp')
                            ->directory('product_photos') // Store photos in a 'product_photos' directory
                            ->visibility('public'), // Make photos publicly accessible
                    ])
                    ->label('Additional Photos')
                    ->helperText('Upload additional images for the product (JPG or PNG).')
                    ->defaultItems(0), // Start with no additional photo fields by default
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\ImageColumn::make('thumbnail'),

                Tables\Columns\TextColumn::make('price')
                    ->money('IDR') // Assuming price is stored as a number and should be formatted
                    ->sortable(),

                // Display sizes and stock (optional, can be complex in a table column)
                // Tables\Columns\TextColumn::make('productSizes.size')
                //     ->label('Sizes'),
                // Tables\Columns\TextColumn::make('productSizes.stock')
                //     ->label('Stock'),
            ])
            ->filters([
                // Add filters for sizes/stock if needed, this would require custom filters
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
            PhotosRelationManager::class,
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
}
