<?php

namespace App\Filament\Resources\Redirects\Pages;

use App\Filament\Resources\Redirects\RedirectResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListRedirects extends ListRecords
{
    protected static string $resource = RedirectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
