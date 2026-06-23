<?php

namespace App\Filament\Resources\PageTypes\Pages;

use App\Filament\Resources\PageTypes\PageTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPageTypes extends ListRecords
{
    protected static string $resource = PageTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
