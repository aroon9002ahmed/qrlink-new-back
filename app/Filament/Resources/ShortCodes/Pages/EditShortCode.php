<?php

namespace App\Filament\Resources\ShortCodes\Pages;

use App\Filament\Resources\ShortCodes\ShortCodeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditShortCode extends EditRecord
{
    protected static string $resource = ShortCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
