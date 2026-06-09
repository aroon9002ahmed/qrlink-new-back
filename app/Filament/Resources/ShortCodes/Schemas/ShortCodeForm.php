<?php

namespace App\Filament\Resources\ShortCodes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ShortCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('codeable_type')
                    ->required(),
                TextInput::make('codeable_id')
                    ->required()
                    ->numeric(),
                TextInput::make('clicks')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
