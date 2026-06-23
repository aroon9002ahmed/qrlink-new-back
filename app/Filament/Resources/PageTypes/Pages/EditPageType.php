<?php

namespace App\Filament\Resources\PageTypes\Pages;

use App\Filament\Resources\PageTypes\PageTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPageType extends EditRecord
{
    protected static string $resource = PageTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
