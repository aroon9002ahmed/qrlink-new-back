<?php

namespace App\Filament\Resources\Redirects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RedirectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('old_path')
                    ->label('Old Path / URL')
                    ->placeholder('/old-about-us')
                    ->helperText('Relative path (e.g. /old-page) or full URL')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('new_path')
                    ->label('Target Path / URL')
                    ->placeholder('/about')
                    ->helperText('Target relative path (e.g. /about) or external URL (https://...)')
                    ->required()
                    ->maxLength(255),

                Select::make('status_code')
                    ->label('HTTP Redirect Code')
                    ->options([
                        301 => '301 - Permanent Redirect (Recommended for SEO)',
                        302 => '302 - Temporary Redirect',
                    ])
                    ->default(301)
                    ->required(),

                Toggle::make('is_active')
                    ->label('Is Active')
                    ->default(true),

                TextInput::make('hits')
                    ->label('Total Hits')
                    ->disabled()
                    ->default(0)
                    ->visibleOn('edit'),
            ]);
    }
}
