<?php

namespace App\Filament\Resources\PageTypes;

use App\Filament\Resources\PageTypes\Pages\CreatePageType;
use App\Filament\Resources\PageTypes\Pages\EditPageType;
use App\Filament\Resources\PageTypes\Pages\ListPageTypes;
use App\Filament\Resources\PageTypes\Pages\ViewPageType;
use App\Filament\Resources\PageTypes\Schemas\PageTypeForm;
use App\Filament\Resources\PageTypes\Schemas\PageTypeInfolist;
use App\Filament\Resources\PageTypes\Tables\PageTypesTable;
use App\Models\PageType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PageTypeResource extends Resource
{
    protected static ?string $model = PageType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PageTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PageTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PageTypesTable::configure($table);
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
            'index' => ListPageTypes::route('/'),
            'create' => CreatePageType::route('/create'),
            'view' => ViewPageType::route('/{record}'),
            'edit' => EditPageType::route('/{record}/edit'),
        ];
    }
}
