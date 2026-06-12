<?php

namespace App\Filament\Resources\Blacklists;

use App\Filament\Resources\Blacklists\Pages\CreateBlacklists;
use App\Filament\Resources\Blacklists\Pages\EditBlacklists;
use App\Filament\Resources\Blacklists\Pages\ListBlacklists;
use App\Filament\Resources\Blacklists\Schemas\BlacklistForm;
use App\Filament\Resources\Blacklists\Tables\BlacklistTable;
use App\Models\Blacklist;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BlacklistResource extends Resource
{
    protected static ?string $model = Blacklist::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'domain';

    public static function form(Schema $schema): Schema
    {
        return BlacklistForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlacklistTable::configure($table);
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
            'index' => ListBlacklists::route('/'),
            'create' => CreateBlacklists::route('/create'),
            'edit' => EditBlacklists::route('/{record}/edit'),
        ];
    }
}
