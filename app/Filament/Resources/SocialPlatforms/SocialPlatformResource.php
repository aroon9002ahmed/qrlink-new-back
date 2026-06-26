<?php

namespace App\Filament\Resources\SocialPlatforms;

use App\Filament\Resources\SocialPlatforms\Pages\CreateSocialPlatform;
use App\Filament\Resources\SocialPlatforms\Pages\EditSocialPlatform;
use App\Filament\Resources\SocialPlatforms\Pages\ListSocialPlatforms;
use App\Filament\Resources\SocialPlatforms\Schemas\SocialPlatformForm;
use App\Filament\Resources\SocialPlatforms\Tables\SocialPlatformTable;
use App\Models\SocialPlatform;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SocialPlatformResource extends Resource
{
    protected static ?string $model = SocialPlatform::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SocialPlatformForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SocialPlatformTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSocialPlatforms::route('/'),
            'create' => CreateSocialPlatform::route('/create'),
            'edit' => EditSocialPlatform::route('/{record}/edit'),
        ];
    }
}
