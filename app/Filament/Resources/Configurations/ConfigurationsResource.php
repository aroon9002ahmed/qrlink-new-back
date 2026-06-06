<?php

namespace App\Filament\Resources\Configurations;

use App\Filament\Resources\Configurations\Pages\CreateConfigurations;
use App\Filament\Resources\Configurations\Pages\EditConfigurations;
use App\Filament\Resources\Configurations\Pages\ListConfigurations;
use App\Filament\Resources\Configurations\Schemas\ConfigurationsForm;
use App\Filament\Resources\Configurations\Tables\ConfigurationsTable;
use App\Models\Configurations;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConfigurationsResource extends Resource
{
    protected static ?string $model = Configurations::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'slug';

    public static function form(Schema $schema): Schema
    {
        return ConfigurationsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConfigurationsTable::configure($table);
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
            'index' => ListConfigurations::route('/'),
            'create' => CreateConfigurations::route('/create'),
            'edit' => EditConfigurations::route('/{record}/edit'),
        ];
    }
}
