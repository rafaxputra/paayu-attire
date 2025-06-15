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
use Filament\Tables\Actions\Action; // Added for custom actions
use Filament\Tables\Actions\ActionGroup; // Added for action grouping

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
                    ->options([
                        'pending_payment_verification' => 'Pending Payment Verification',
                        'payment_validated' => 'Payment Validated',
                        'payment_failed' => 'Payment Failed',
                        'ready_for_pickup' => 'Ready for Pickup',
                        'in_rental' => 'In Rental',
                        'completed' => 'Completed',
                        'late_returned' => 'Late Returned',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('selected_size')
                    ->label('Selected Size')
                    ->maxLength(10)
                    ->disabled(),
                Forms\Components\TextInput::make('late_days')
                    ->numeric()
                    ->label('Terlambat (hari)')
                    ->nullable()
                    ->helperText('Jika status late_returned, sistem otomatis menghitung denda berdasarkan hari ini dan ended_at. Bisa diubah manual.'),
                Forms\Components\TextInput::make('late_fee')
                    ->numeric()
                    ->prefix('IDR')
                    ->label('Total Denda')
                    ->nullable()
                    ->helperText('Denda dihitung otomatis: 20% x total_amount x late_days. Bisa diubah manual.'),
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
                    ->badge() // Display status as a badge
                    ->color(fn (RentalTransactionStatus $state): string => match ($state) {
                        RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION => 'warning',
                        RentalTransactionStatus::PAYMENT_VALIDATED => 'success',
                        RentalTransactionStatus::PAYMENT_FAILED => 'danger',
                        RentalTransactionStatus::IN_RENTAL => 'info',
                        RentalTransactionStatus::COMPLETED => 'success',
                        RentalTransactionStatus::LATE_RETURNED => 'danger', // Added late_returned status
                        RentalTransactionStatus::REJECTED => 'danger',
                        RentalTransactionStatus::CANCELLED => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('selected_size')
                    ->label('Selected Size')
                    ->sortable(),
                Tables\Columns\TextColumn::make('late_days')
                    ->label('Terlambat (hari)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('late_fee')
                    ->label('Total Denda')
                    ->money('IDR')
                    ->sortable(),
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
                ActionGroup::make([
                    Action::make('validate_payment')
                        ->label('Validate Payment')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (RentalTransaction $record): bool => $record->status === RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION || $record->status === RentalTransactionStatus::PAYMENT_FAILED)
                        ->action(function (RentalTransaction $record) {
                            $record->status = RentalTransactionStatus::PAYMENT_VALIDATED;
                            
                            $record->save();
                        }),
                    Action::make('invalidate_payment')
                        ->label('Invalidate Payment')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (RentalTransaction $record): bool =>
                            $record->status === RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION ||
                            $record->status === RentalTransactionStatus::PAYMENT_VALIDATED ||
                            $record->status === RentalTransactionStatus::IN_RENTAL
                        )
                        ->action(function (RentalTransaction $record) {
                            $record->status = RentalTransactionStatus::PAYMENT_FAILED;
                            
                            $record->save();
                        }),
                    Action::make('mark_as_picked_up')
                        ->label('Mark as Picked Up')
                        ->icon('heroicon-o-truck')
                        ->color('primary')
                        ->visible(fn (RentalTransaction $record): bool => $record->status === RentalTransactionStatus::PAYMENT_VALIDATED) // Only visible after payment is validated
                        ->action(function (RentalTransaction $record) {
                            $record->status = RentalTransactionStatus::IN_RENTAL;
                            $record->save();
                        }),
                    Action::make('mark_as_completed')
                        ->label('Mark as Completed')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn (RentalTransaction $record): bool => $record->status === RentalTransactionStatus::IN_RENTAL || $record->status === RentalTransactionStatus::LATE_RETURNED)
                        ->action(function (RentalTransaction $record) {
                            $record->status = RentalTransactionStatus::COMPLETED;
                            $record->save();
                        }),
                    Action::make('cancel_booking')
                        ->label('Cancel Booking')
                        ->icon('heroicon-o-no-symbol')
                        ->color('gray')
                        ->visible(fn (RentalTransaction $record): bool =>
                            $record->status !== RentalTransactionStatus::COMPLETED &&
                            $record->status !== RentalTransactionStatus::REJECTED &&
                            $record->status !== RentalTransactionStatus::CANCELLED
                        )
                        ->action(function (RentalTransaction $record) {
                            $record->status = RentalTransactionStatus::CANCELLED;
                            $record->save();
                        }),
                    Action::make('reject_booking')
                        ->label('Reject Booking')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (RentalTransaction $record): bool =>
                            $record->status === RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION
                        )
                        ->action(function (RentalTransaction $record) {
                            $record->status = RentalTransactionStatus::REJECTED;
                            $record->save();
                        }),
                    Action::make('mark_as_late_returned')
                        ->label('Mark as Late Returned')
                        ->icon('heroicon-o-clock')
                        ->color('danger')
                        ->visible(fn (RentalTransaction $record): bool => $record->status === RentalTransactionStatus::IN_RENTAL)
                        ->form([
                            Forms\Components\TextInput::make('late_days')
                                ->label('Late Days')
                                ->numeric()
                                ->required(),
                        ])
                        ->action(function (RentalTransaction $record, array $data) {
                            $lateDays = (int)($data['late_days'] ?? 0);
                            $lateFee = $lateDays > 0 ? $record->total_amount * 0.2 * $lateDays : 0;
                            $record->late_days = $lateDays;
                            $record->late_fee = $lateFee;
                            $record->status = RentalTransactionStatus::LATE_RETURNED;
                            $record->save();
                        }),
                ])
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
