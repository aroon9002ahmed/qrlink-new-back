<?php

namespace App\Filament\Resources\ShortCodes\Pages;

use App\Filament\Resources\ShortCodes\ShortCodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShortCodes extends ListRecords
{
    protected static string $resource = ShortCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
