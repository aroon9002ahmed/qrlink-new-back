<?php

namespace App\Filament\Resources\ShortCodes\Pages;

use App\Filament\Resources\ShortCodes\ShortCodeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewShortCode extends ViewRecord
{
    protected static string $resource = ShortCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
