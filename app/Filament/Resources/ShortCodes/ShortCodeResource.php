<?php

namespace App\Filament\Resources\ShortCodes;

use App\Filament\Resources\ShortCodes\Pages\CreateShortCode;
use App\Filament\Resources\ShortCodes\Pages\EditShortCode;
use App\Filament\Resources\ShortCodes\Pages\ListShortCodes;
use App\Filament\Resources\ShortCodes\Pages\ViewShortCode;
use App\Filament\Resources\ShortCodes\Schemas\ShortCodeForm;
use App\Filament\Resources\ShortCodes\Schemas\ShortCodeInfolist;
use App\Filament\Resources\ShortCodes\Tables\ShortCodesTable;
use App\Models\ShortCode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShortCodeResource extends Resource
{
    protected static ?string $model = ShortCode::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return ShortCodeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ShortCodeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShortCodesTable::configure($table);
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
            'index' => ListShortCodes::route('/'),
            'create' => CreateShortCode::route('/create'),
            'view' => ViewShortCode::route('/{record}'),
            'edit' => EditShortCode::route('/{record}/edit'),
        ];
    }
}
