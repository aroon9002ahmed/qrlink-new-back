<?php

namespace App\Filament\Resources\PageTypes\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PageTypeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('description')
                    ->placeholder('-'),
                ImageEntry::make('icon')
                    ->disk('public')
                    ->getStateUsing(function ($record) {
                        if (!$record->icon) {
                            return null;
                        }
                        return str_starts_with($record->icon, 'images/pageTypes/cache/')
                            ? $record->icon
                            : 'images/pageTypes/cache/' . $record->icon;
                    })
                    ->placeholder('-'),
                IconEntry::make('status')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
