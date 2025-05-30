<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalTransactionResource\Pages;
use App\Filament\Resources\RentalTransactionResource\RelationManagers;
use App\Models\RentalTransaction;
use Filament\Forms;
use App\Enums\RentalTransactionStatus; // Import the enum
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User; // Import User model

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
                Forms\Components\Select::make('user_id') // Added user_id field
                    ->relationship('user', 'name') // Link to User model, display name
                    ->required()
                    ->searchable()
                    ->preload(),
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
                    ->visibility('public')
                    ->disabled(), // Payment proof should not be editable after upload
                Forms\Components\Select::make('payment_method') // Changed to Select
                    ->options([
                        'BCA' => 'BCA',
                        'BRI' => 'BRI',
                    ])
                    ->disabled(), // Payment method should not be editable after upload
                Forms\Components\Select::make('status') // Changed to Select
                    ->options(RentalTransactionStatus::class) // Use enum for options
                    ->required()
                    ->default(RentalTransactionStatus::PENDING), // Use enum default
                Forms\Components\Toggle::make('is_paid')
                    ->required()
                    ->disabled(), // Disable is_paid toggle in the form
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('trx_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name') // Display user's name
                    ->label('User Name')
                    ->searchable()
                    ->sortable(),
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
                    ->badge(), // Display status as a badge, Filament will use the enum's HasColor and HasLabel
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean(),
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
                    ->visible(fn (RentalTransaction $record): bool => $record->status === RentalTransactionStatus::PENDING) // Use enum
                    ->action(function (RentalTransaction $record) {
                        $record->update(['status' => RentalTransactionStatus::ACCEPTED]); // Use enum
                    }),
                Tables\Actions\Action::make('rejectOrder')
                    ->label('Reject Order')
                    ->button()
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->visible(fn (RentalTransaction $record): bool => $record->status === RentalTransactionStatus::PENDING) // Use enum
                    ->action(function (RentalTransaction $record) {
                        $record->update(['status' => RentalTransactionStatus::REJECTED]); // Use enum
                    }),
                Tables\Actions\Action::make('verifyPayment')
                    ->label('Verify Payment')
                    ->button()
                    ->color('success')
                    ->icon('heroicon-o-currency-dollar')
                    ->requiresConfirmation()
                    ->visible(fn (RentalTransaction $record): bool => $record->status === RentalTransactionStatus::PENDING_PAYMENT && !$record->is_paid) // Visible when pending payment and not paid
                    ->action(function (RentalTransaction $record) {
                        $record->update([
                            'is_paid' => true,
                            'status' => RentalTransactionStatus::PAID, // Change status to Paid after payment verification
                        ]);
                    }),
                 Tables\Actions\Action::make('markAsInRental')
                    ->label('Mark as In Rental')
                    ->button()
                    ->color('primary')
                    ->icon('heroicon-o-truck')
                    ->requiresConfirmation()
                    ->visible(fn (RentalTransaction $record): bool => $record->status === RentalTransactionStatus::PAID) // Visible when paid
                    ->action(function (RentalTransaction $record) {
                        $record->update(['status' => RentalTransactionStatus::IN_RENTAL]); // Use enum
                    }),
                Tables\Actions\Action::make('markAsCompleted')
                    ->label('Mark as Completed')
                    ->button()
                    ->color('success')
                    ->icon('heroicon-o-check-badge')
                    ->requiresConfirmation()
                    ->visible(fn (RentalTransaction $record): bool => $record->status === RentalTransactionStatus::IN_RENTAL) // Visible when in rental
                    ->action(function (RentalTransaction $record) {
                        $record->update(['status' => RentalTransactionStatus::COMPLETED]); // Use enum
                    }),
                 Tables\Actions\Action::make('cancelOrder')
                    ->label('Cancel Order')
                    ->button()
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->visible(fn (RentalTransaction $record): bool => $record->status !== RentalTransactionStatus::COMPLETED && $record->status !== RentalTransactionStatus::CANCELLED && $record->status !== RentalTransactionStatus::REJECTED) // Visible unless completed, cancelled, or rejected
                    ->action(function (RentalTransaction $record) {
                        $record->update(['status' => RentalTransactionStatus::CANCELLED]); // Use enum
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
            'index' => Pages\ListRentalTransactions::route('/'),
            'create' => Pages\CreateRentalTransaction::route('/create'),
            'edit' => Pages\EditRentalTransaction::route('/{record}/edit'),
        ];
    }
}
