<?php

namespace App\Filament\Resources\PageTypes\Pages;

use App\Filament\Resources\PageTypes\PageTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPageType extends ViewRecord
{
    protected static string $resource = PageTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
