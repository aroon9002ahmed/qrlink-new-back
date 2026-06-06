<?php

namespace App\Filament\Resources\Configurations\Pages;

use App\Filament\Resources\Configurations\ConfigurationsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditConfigurations extends EditRecord
{
    protected static string $resource = ConfigurationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
