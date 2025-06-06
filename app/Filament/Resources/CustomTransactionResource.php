<?php

namespace App\Filament\Resources;

use App\Enums\CustomTransactionStatus;
use App\Filament\Resources\CustomTransactionResource\Pages;
use App\Filament\Resources\CustomTransactionResource\RelationManagers;
use App\Models\CustomTransaction;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomTransactionResource extends Resource
{
protected static ?string $model = CustomTransaction::class;

protected static ?string $navigationIcon = 'heroicon-o-pencil';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('trx_id')
                ->required()
                ->maxLength(255)
                ->disabled(),
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
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
                ->directory('custom_kebaya_references')
                ->disabled(),
            Forms\Components\FileUpload::make('image_reference_2')
                ->label('Image Reference 2')
                ->image()
                ->optimize('webp')
                ->directory('custom_kebaya_references')
                ->nullable(),
            Forms\Components\FileUpload::make('image_reference_3')
                ->label('Image Reference 3')
                ->image()
                ->optimize('webp')
                ->directory('custom_kebaya_references')
                ->nullable(),
            Forms\Components\TextInput::make('material')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('color')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('selected_size_chart')
                ->label('Selected Size')
                ->options([
                    'S' => 'S',
                    'M' => 'M',
                    'L' => 'L',
                    'XL' => 'XL',
                    'custom' => 'Custom Size',
                ])
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, $set) {
                    if ($state !== 'custom') {
                        $set('lebar_bahu_belakang', null);
                        $set('lingkar_panggul', null);
                        $set('lingkar_pinggul', null);
                        $set('lingkar_dada', null);
                        $set('kerung_lengan', null);
                    }
                }),
            Forms\Components\Fieldset::make('Custom Measurements')
                ->visible(fn(Forms\Get $get): bool => $get('selected_size_chart') === 'custom')
                ->schema([
                    Forms\Components\TextInput::make('lebar_bahu_belakang')
                        ->label('Lebar Bahu Belakang (cm)')
                        ->numeric()
                        ->required(fn(Forms\Get $get): bool => $get('selected_size_chart') === 'custom'),
                    Forms\Components\TextInput::make('lingkar_panggul')
                        ->label('Lingkar Panggul (cm)')
                        ->numeric()
                        ->required(fn(Forms\Get $get): bool => $get('selected_size_chart') === 'custom'),
                    Forms\Components\TextInput::make('lingkar_pinggul')
                        ->label('Lingkar Pinggul (cm)')
                        ->numeric()
                        ->required(fn(Forms\Get $get): bool => $get('selected_size_chart') === 'custom'),
                    Forms\Components\TextInput::make('lingkar_dada')
                        ->label('Lingkar Dada (cm)')
                        ->numeric()
                        ->required(fn(Forms\Get $get): bool => $get('selected_size_chart') === 'custom'),
                    Forms\Components\TextInput::make('kerung_lengan')
                        ->label('Kerung Lengan (cm)')
                        ->numeric()
                        ->required(fn(Forms\Get $get): bool => $get('selected_size_chart') === 'custom'),
                ]),
            Forms\Components\Textarea::make('kebaya_preference')
                ->required()
                ->columnSpanFull()
                ->disabled(),
            Forms\Components\TextInput::make('amount_to_buy')
                ->label('Quantity to Buy')
                ->required()
                ->numeric()
                ->minValue(1)
                ->disabled(),
            Forms\Components\DatePicker::make('date_needed')
                ->required()
                ->disabled(),
            Forms\Components\TextInput::make('admin_price')
                ->numeric()
                ->prefix('IDR')
                ->visible(fn(string $operation): bool => $operation === 'edit'),
            Forms\Components\DatePicker::make('admin_estimated_completion_date')
                ->visible(fn(string $operation): bool => $operation === 'edit'),
            Forms\Components\Select::make('status')
                ->options(CustomTransactionStatus::class)
                ->required()
                ->default(CustomTransactionStatus::PENDING),
            Forms\Components\FileUpload::make('payment_proof')
                ->image()
                ->optimize('webp')
                ->directory('custom_payment_proofs')
                ->disabled(),
            Forms\Components\Select::make('payment_method')
                ->options([
                    'BCA' => 'BCA',
                    'BRI' => 'BRI',
                ])
                ->disabled(),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('trx_id')
                ->searchable(),
            Tables\Columns\TextColumn::make('user.name')
                ->label('User Name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('name')
                ->searchable(),
            Tables\Columns\TextColumn::make('phone_number')
                ->searchable(),
            Tables\Columns\ImageColumn::make('image_reference')
                ->label('Ref Image 1'),
            Tables\Columns\ImageColumn::make('image_reference_2')
                ->label('Ref Image 2'),
            Tables\Columns\ImageColumn::make('image_reference_3')
                ->label('Ref Image 3'),
            Tables\Columns\TextColumn::make('material')
                ->searchable(),
            Tables\Columns\TextColumn::make('color')
                ->searchable(),
            Tables\Columns\TextColumn::make('selected_size_chart')
                ->label('Selected Size')
                ->searchable(),
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
                ->money('IDR'),
            Tables\Columns\TextColumn::make('admin_estimated_completion_date')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->searchable()
                ->badge(),
            Tables\Columns\ImageColumn::make('payment_proof')
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
                ->visible(fn(CustomTransaction $record): bool => $record->status === CustomTransactionStatus::PENDING)
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
                ->visible(fn(CustomTransaction $record): bool => $record->status === CustomTransactionStatus::PENDING)
                ->action(function (CustomTransaction $record) {
                    $record->update(['status' => CustomTransactionStatus::REJECTED]);
                }),
            Action::make('validatePayment')
                ->label('Validate Payment')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn(CustomTransaction $record): bool => $record->status === CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION && $record->payment_proof !== null)
                ->action(function (CustomTransaction $record) {
                    $record->update(['status' => CustomTransactionStatus::PAYMENT_VALIDATED]);
                }),
            Action::make('invalidatePayment')
                ->label('Invalidate Payment')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn(CustomTransaction $record): bool => $record->status === CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION && $record->payment_proof !== null)
                ->action(function (CustomTransaction $record) {
                    $record->update(['status' => CustomTransactionStatus::PAYMENT_FAILED]);
                }),
            Action::make('markAsInProgress')
                ->label('Mark as In Progress')
                ->icon('heroicon-o-arrow-right')
                ->color('info')
                ->requiresConfirmation()
                ->visible(fn(CustomTransaction $record): bool => $record->status === CustomTransactionStatus::PAYMENT_VALIDATED)
                ->action(function (CustomTransaction $record) {
                    $record->update(['status' => CustomTransactionStatus::IN_PROGRESS]);
                }),
            Action::make('markAsCompleted')
                ->label('Mark as Completed')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn(CustomTransaction $record): bool => $record->status === CustomTransactionStatus::IN_PROGRESS)
                ->action(function (CustomTransaction $record) {
                    $record->update(['status' => CustomTransactionStatus::COMPLETED]);
                }),
            Action::make('cancelOrder')
                ->label('Cancel Order')
                ->icon('heroicon-o-no-symbol')
                ->color('gray')
                ->requiresConfirmation()
                ->visible(fn(CustomTransaction $record): bool =>
                    $record->status !== CustomTransactionStatus::COMPLETED &&
                    $record->status !== CustomTransactionStatus::REJECTED &&
                    $record->status !== CustomTransactionStatus::CANCELLED)
                ->action(function (CustomTransaction $record) {
                    $record->update(['status' => CustomTransactionStatus::CANCELLED]);
                }),
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
