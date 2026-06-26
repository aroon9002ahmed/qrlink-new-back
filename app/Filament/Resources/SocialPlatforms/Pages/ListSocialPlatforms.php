<?php

namespace App\Filament\Resources\SocialPlatforms\Pages;

use App\Filament\Resources\SocialPlatforms\SocialPlatformResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSocialPlatforms extends ListRecords
{
    protected static string $resource = SocialPlatformResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
