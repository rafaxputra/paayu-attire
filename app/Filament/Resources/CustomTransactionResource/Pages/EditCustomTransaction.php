<?php

namespace App\Filament\Resources\CustomTransactionResource\Pages;

use App\Filament\Resources\CustomTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomTransaction extends EditRecord
{
    protected static string $resource = CustomTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
