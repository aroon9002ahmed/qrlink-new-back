<?php

namespace App\Filament\Resources\Configurations\Pages;

use App\Filament\Resources\Configurations\ConfigurationsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConfigurations extends ListRecords
{
    protected static string $resource = ConfigurationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
