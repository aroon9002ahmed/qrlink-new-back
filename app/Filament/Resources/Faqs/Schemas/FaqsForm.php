<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class FaqsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Translations')
                    ->tabs([
                        Tab::make('English')
                            ->schema([
                                TextInput::make('question.en')
                                    ->label('Question (EN)')
                                    ->required()
                                    ->afterStateHydrated(fn ($state, $record, $component) => $component->state($record?->getTranslation('question', 'en'))),
                                TextInput::make('answer.en')
                                    ->label('Answer (EN)')
                                    ->required()
                                    ->afterStateHydrated(fn ($state, $record, $component) => $component->state($record?->getTranslation('answer', 'en'))),
                            ]),
                        Tab::make('Arabic')
                            ->schema([
                                TextInput::make('question.ar')
                                    ->label('Question (AR)')
                                    ->required()
                                    ->afterStateHydrated(fn ($state, $record, $component) => $component->state($record?->getTranslation('question', 'ar'))),
                                TextInput::make('answer.ar')
                                    ->label('Answer (AR)')
                                    ->required()
                                    ->afterStateHydrated(fn ($state, $record, $component) => $component->state($record?->getTranslation('answer', 'ar'))),
                            ]),
                    ])
                    ->columnSpanFull(),

                Toggle::make('status')
                    ->required(),
                TextInput::make('order')
                    ->required()
                    ->numeric(),
            ]);
    }
}
