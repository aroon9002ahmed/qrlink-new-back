<?php

namespace App\Filament\Resources\SocialPlatforms\Pages;

use App\Filament\Resources\SocialPlatforms\SocialPlatformResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSocialPlatform extends EditRecord
{
    protected static string $resource = SocialPlatformResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
