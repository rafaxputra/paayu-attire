<?php

namespace App\Filament\Resources\ProductSizeResource\Pages;

use App\Filament\Resources\ProductSizeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductSizes extends ListRecords
{
    protected static string $resource = ProductSizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
