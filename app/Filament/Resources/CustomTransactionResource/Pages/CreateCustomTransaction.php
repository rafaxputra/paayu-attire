<?php

namespace App\Filament\Resources\CustomTransactionResource\Pages;

use App\Filament\Resources\CustomTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomTransaction extends CreateRecord
{
    protected static string $resource = CustomTransactionResource::class;
}
