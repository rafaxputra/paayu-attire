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
use App\Models\User; // Import User model
use Filament\Tables\Actions\Action; // Added for custom actions
use Filament\Tables\Actions\ActionGroup; // Added for action grouping
use Filament\Forms\Components\TextInput; // Import TextInput
use Filament\Forms\Components\DatePicker; // Import DatePicker

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
                Forms\Components\Select::make('user_id') // Added user_id field
                    ->relationship('user', 'name') // Link to User model, display name
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
                Forms\Components\FileUpload::make('image_reference')
                    ->label('Image Reference 1')
                    ->image()
                    ->optimize('webp')
                    ->directory('custom_kebaya_references') // Store in custom_kebaya_references directory
                    ->disabled(), // Image reference should not be editable after creation
                Forms\Components\FileUpload::make('image_reference_2') // Added image_reference_2 field
                    ->label('Image Reference 2')
                    ->image()
                    ->optimize('webp')
                    ->directory('custom_kebaya_references') // Store in custom_kebaya_references directory
                    ->nullable(), // Make nullable as per database
                Forms\Components\FileUpload::make('image_reference_3') // Added image_reference_3 field
                    ->label('Image Reference 3')
                    ->image()
                    ->optimize('webp')
                    ->directory('custom_kebaya_references') // Store in custom_kebaya_references directory
                    ->nullable(), // Make nullable as per database
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
                    ->options(CustomTransactionStatus::class) // Use enum for options
                    ->required()
                    ->default(CustomTransactionStatus::PENDING), // Use enum default
                Forms\Components\FileUpload::make('payment_proof') // Changed to FileUpload
                    ->image()
                    ->optimize('webp')
                    ->directory('custom_payment_proofs') // Store in custom_payment_proofs directory
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
                Tables\Columns\TextColumn::make('user.name') // Display user's name
                    ->label('User Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image_reference')
                    ->label('Ref Image 1'), // Added label
                Tables\Columns\ImageColumn::make('image_reference_2') // Added image_reference_2 to table
                    ->label('Ref Image 2'), // Added label
                Tables\Columns\ImageColumn::make('image_reference_3') // Added image_reference_3 to table
                    ->label('Ref Image 3'), // Added label
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
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge(), // Display status as a badge, Filament will use the enum's HasColor and HasLabel
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
                Action::make('provideEstimate')
                    ->label('Provide Estimate')
                    ->icon('heroicon-o-currency-dollar')
                    ->visible(fn (CustomTransaction $record): bool => $record->status === CustomTransactionStatus::PENDING)
                    ->form([
                        TextInput::make('admin_price')
                            ->label('Estimated Price (IDR)')
                            ->required()
                            ->numeric()
                            ->prefix('IDR'),
                        DatePicker::make('admin_estimated_completion_date')
                            ->label('Estimated Completion Date')
                            ->required(),
                    ])
                    ->action(function (CustomTransaction $record, array $data): void {
                        $record->admin_price = $data['admin_price'];
                        $record->admin_estimated_completion_date = $data['admin_estimated_completion_date'];
                        $record->status = CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION;
                        $record->save();
                    }),
                Action::make('rejectOrder')
                    ->label('Reject Order')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (CustomTransaction $record): bool => $record->status === CustomTransactionStatus::PENDING)
                    ->action(function (CustomTransaction $record) {
                        $record->update(['status' => CustomTransactionStatus::REJECTED]);
                    }),
                Action::make('validatePayment')
                    ->label('Validate Payment')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (CustomTransaction $record): bool => $record->status === CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION && $record->payment_proof !== null)
                    ->action(function (CustomTransaction $record) {
                        $record->update(['status' => CustomTransactionStatus::PAYMENT_VALIDATED]);
                    }),
                 Action::make('invalidatePayment')
                    ->label('Invalidate Payment')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (CustomTransaction $record): bool => $record->status === CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION && $record->payment_proof !== null)
                    ->action(function (CustomTransaction $record) {
                        $record->update(['status' => CustomTransactionStatus::PAYMENT_FAILED]);
                    }),
                Action::make('markAsInProgress')
                    ->label('Mark as In Progress')
                    ->icon('heroicon-o-arrow-right')
                    ->color('info')
                     ->requiresConfirmation()
                    ->visible(fn (CustomTransaction $record): bool => $record->status === CustomTransactionStatus::PAYMENT_VALIDATED)
                    ->action(function (CustomTransaction $record) {
                        $record->update(['status' => CustomTransactionStatus::IN_PROGRESS]);
                    }),
                Action::make('markAsCompleted')
                    ->label('Mark as Completed')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (CustomTransaction $record): bool => $record->status === CustomTransactionStatus::IN_PROGRESS)
                    ->action(function (CustomTransaction $record) {
                        $record->update(['status' => CustomTransactionStatus::COMPLETED]);
                    }),
                 Action::make('cancelOrder')
                    ->label('Cancel Order')
                    ->icon('heroicon-o-no-symbol')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->visible(fn (CustomTransaction $record): bool =>
                        $record->status !== CustomTransactionStatus::COMPLETED &&
                        $record->status !== CustomTransactionStatus::REJECTED &&
                        $record->status !== CustomTransactionStatus::CANCELLED
                    )
                    ->action(function (CustomTransaction $record) {
                        $record->update(['status' => CustomTransactionStatus::CANCELLED]);
                    }),
                Tables\Actions\EditAction::make(), // Keep edit action
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
