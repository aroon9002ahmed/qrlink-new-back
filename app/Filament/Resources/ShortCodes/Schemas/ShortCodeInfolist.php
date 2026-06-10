<?php

namespace App\Filament\Resources\ShortCodes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ShortCodeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code'),
                TextEntry::make('code')
                    ->label('Live URL')
                    ->url(fn($record) => env('APP_URL') . "/" . $record->code)
                    ->openUrlInNewTab(),
                TextEntry::make('codeable_type'),
                TextEntry::make('codeable_id')
                    ->numeric(),
                TextEntry::make('clicks')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
