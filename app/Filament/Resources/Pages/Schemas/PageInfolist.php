<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->columnSpanFull(),

                TextEntry::make('slug')
                    ->url(fn ($record): string => rtrim(env('FRONTEND_URL', config('app.url')), '/') . '/p/' . $record->slug)
                    ->openUrlInNewTab(),

                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('user.name')
                    ->label('User'),

                TextEntry::make('user.email')
                    ->label('User Email'),

                TextEntry::make('pageType.name')
                    ->label('Page Type')
                    ->placeholder('-'),

                TextEntry::make('template.name')
                    ->label('Template')
                    ->placeholder('-'),

                TextEntry::make('language')
                    ->placeholder('-'),

                TextEntry::make('copyright')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state ? 'Active' : 'Deactive')
                    ->color(fn ($state): string => $state ? 'success' : 'danger')
                    ->placeholder('-'),

                TextEntry::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state ? 'Active' : 'Inactive')
                    ->color(fn ($state): string => $state ? 'success' : 'danger'),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
