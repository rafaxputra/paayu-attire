<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomTransactionResource\Pages;
use App\Filament\Resources\CustomTransactionResource\RelationManagers;
use App\Models\CustomTransaction;
use Filament\Forms;
use App\Enums\CustomTransactionStatus; // Import the enum
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomTransactionResource extends Resource
{
    protected static ?string $model = CustomTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil'; // Changed icon

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('trx_id')
                    ->required()
                    ->maxLength(255)
                    ->disabled(), // Transaction ID should not be editable
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image_reference')
                    ->image()
                    ->optimize('webp')
                    ->directory('custom_kebaya_references') // Store in custom_kebaya_references directory
                    ->visibility('public') // Make publicly accessible
                    ->disabled(), // Image reference should not be editable after creation
                Forms\Components\Textarea::make('kebaya_preference')
                    ->required()
                    ->columnSpanFull()
                    ->disabled(), // Kebaya preference should not be editable after creation
                Forms\Components\TextInput::make('amount_to_buy')
                    ->label('Quantity to Buy')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->disabled(), // Quantity should not be editable after creation
                Forms\Components\DatePicker::make('date_needed')
                    ->required()
                    ->disabled(), // Date needed should not be editable after creation
                Forms\Components\TextInput::make('admin_price')
                    ->numeric()
                    ->prefix('IDR')
                    ->visible(fn (string $operation): bool => $operation === 'edit'), // Only visible on edit
                Forms\Components\DatePicker::make('admin_estimated_completion_date')
                    ->visible(fn (string $operation): bool => $operation === 'edit'), // Only visible on edit
                Forms\Components\Select::make('status') // Changed to Select
                    ->options(\App\Enums\CustomTransactionStatus::class) // Use enum for options
                    ->required()
                    ->default('pending'),
                Forms\Components\Toggle::make('is_paid')
                    ->required(), // is_paid can now be toggled directly
                Forms\Components\FileUpload::make('payment_proof') // Changed to FileUpload
                    ->image()
                    ->optimize('webp')
                    ->directory('custom_payment_proofs') // Store in custom_payment_proofs directory
                    ->visibility('public') // Make publicly accessible
                    ->disabled(), // Payment proof should not be editable after upload
                Forms\Components\Select::make('payment_method') // Changed to Select
                    ->options([
                        'BCA' => 'BCA',
                        'BRI' => 'BRI',
                    ])
                    ->disabled(), // Payment method should not be editable after upload
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('trx_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image_reference'),
                Tables\Columns\TextColumn::make('amount_to_buy')
                    ->label('Quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_needed')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('admin_price')
                    ->numeric()
                    ->sortable()
                    ->money('IDR'), // Format as currency
                Tables\Columns\TextColumn::make('admin_estimated_completion_date')
                    ->date()
                    ->sortable(),

                // Delivery Info Columns
                Tables\Columns\TextColumn::make('delivery_type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge(), // Display status as a badge, Filament will use the enum's HasColor and HasLabel
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean(),
                Tables\Columns\ImageColumn::make('payment_proof') // Changed to ImageColumn
                    ->label('Payment Proof'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
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
                Tables\Actions\Action::make('acceptOrder')
                    ->label('Accept Order')
                    ->button()
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->visible(fn (CustomTransaction $record): bool => $record->status === 'pending') // Only show if status is pending
                    ->action(function (CustomTransaction $record) {
                        $record->update(['status' => 'accepted']);
                    }),
                Tables\Actions\Action::make('rejectOrder')
                    ->label('Reject Order')
                    ->button()
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->visible(fn (CustomTransaction $record): bool => $record->status === 'pending') // Only show if status is pending
                    ->action(function (CustomTransaction $record) {
                        $record->update(['status' => 'rejected']);
                    }),
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
            'index' => Pages\ListCustomTransactions::route('/'),
            'create' => Pages\CreateCustomTransaction::route('/create'),
            'edit' => Pages\EditCustomTransaction::route('/{record}/edit'),
        ];
    }
}
