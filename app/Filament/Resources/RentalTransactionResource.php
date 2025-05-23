<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalTransactionResource\Pages;
use App\Filament\Resources\RentalTransactionResource\RelationManagers;
use App\Models\RentalTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RentalTransactionResource extends Resource
{
    protected static ?string $model = RentalTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar'; // Changed icon

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('trx_id')
                    ->required()
                    ->maxLength(255)
                    ->disabled(), // Transaction ID should not be editable
                Forms\Components\Select::make('product_id') // Changed to Select
                    ->relationship('product', 'name') // Use relationship to display product name
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('started_at')
                    ->required(),
                Forms\Components\DatePicker::make('ended_at')
                    ->required(),
                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->prefix('IDR')
                    ->disabled(), // Total amount should not be editable
                Forms\Components\FileUpload::make('payment_proof') // Changed to FileUpload
                    ->image()
                    ->directory('payment_proofs') // Store in payment_proofs directory
                    ->visibility('public'), // Make publicly accessible
                Forms\Components\Select::make('payment_method') // Changed to Select
                    ->options([
                        'BCA' => 'BCA',
                        'BRI' => 'BRI',
                    ]),
                Forms\Components\Select::make('status') // Changed to Select
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'verified' => 'Pembayaran Terverifikasi',
                        'in_process' => 'Pesanan dalam Proses Produksi',
                        'production_error' => 'Kesalahan Produksi', // Tetap bagian dari tahap ke-3
                        'ready' => 'Pesanan Bisa Diambil',
                    ])
                    ->required()
                    ->default('pending'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('trx_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.name') // Display product name
                    ->label('Product Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ended_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable()
                    ->money('IDR'), // Format as currency
                Tables\Columns\ImageColumn::make('payment_proof') // Changed to ImageColumn
                    ->label('Payment Proof'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge() // Display status as a badge
                    ->color(fn (string $state): string => match ($state) { // Add color coding
                        'pending' => 'warning',
                        'verified' => 'info', // Assuming 'verified' is a status
                        'in_process' => 'primary',
                        'production_error' => 'danger', // Assuming 'production_error' is a status
                        'ready' => 'success',
                        'cancelled' => 'danger', // Assuming 'cancelled' is a status
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Removed markAsPaid action
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentalTransactions::route('/'),
            'create' => Pages\CreateRentalTransaction::route('/create'),
            'edit' => Pages\EditRentalTransaction::route('/{record}/edit'),
        ];
    }
}
