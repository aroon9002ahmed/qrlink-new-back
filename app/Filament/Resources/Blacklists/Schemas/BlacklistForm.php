<?php

namespace App\Filament\Resources\Blacklists\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BlacklistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('domain')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('domain.com')
                    ->helperText('Enter the domain to block (e.g. spamdomain.com). All subdomains will also be blocked.'),

                Textarea::make('reason')
                    ->maxLength(65535)
                    ->placeholder('Optional reason for blacklisting'),

                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
