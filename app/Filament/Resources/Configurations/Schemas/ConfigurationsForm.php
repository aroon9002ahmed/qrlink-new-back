<?php

namespace App\Filament\Resources\Configurations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class ConfigurationsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('inputType')
                    ->options([
                        'string' => 'String',
                        'number' => 'Number',
                        'email' => 'Email',
                        'url' => 'Url',
                        'date' => 'Date',
                        'textarea' => 'Textarea',
                        'textedit' => 'Textedit',
                    ])
                    ->default('string')
                    ->required()
                    ->live()
                    ->disabledOn('edit')
                    ->hint(fn ($operation) => $operation === 'edit' ? "can't change" : null),

                Tabs::make('Translations')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('English')
                            ->schema(fn ($get): array => match ($get('inputType') ?? 'string') {
                                'textarea' => [
                                    Textarea::make('name.en')
                                        ->label('Name (EN)')
                                        ->required()
                                        ->rows(5)
                                        ->placeholder('Configration name (en)')
                                        ->afterStateHydrated(fn ($state, $record, $component) => $component->state($record?->getTranslation('name', 'en'))),
                                ],
                                'textedit' => [
                                    RichEditor::make('name.en')
                                        ->label('Name (EN)')
                                        ->required()
                                        ->afterStateHydrated(fn ($state, $record, $component) => $component->state($record?->getTranslation('name', 'en'))),
                                ],
                                default => [
                                    TextInput::make('name.en')
                                        ->label('Name (EN)')
                                        ->required()
                                        ->type(match ($get('inputType')) {
                                            'number' => 'number',
                                            'email' => 'email',
                                            'url' => 'url',
                                            'date' => 'date',
                                            default => 'text',
                                        })
                                        ->placeholder(match ($get('inputType')) {
                                            'number' => 'Configration name (en) [Numbers only]',
                                            'email' => 'Configration name (en) [Email]',
                                            'url' => 'Configration name (en) [URL]',
                                            default => 'Configration name (en)',
                                        })
                                        ->afterStateHydrated(fn ($state, $record, $component) => $component->state($record?->getTranslation('name', 'en'))),
                                ],
                            }),
                        Tab::make('Arabic')
                            ->schema(fn ($get): array => match ($get('inputType') ?? 'string') {
                                'textarea' => [
                                    Textarea::make('name.ar')
                                        ->label('Name (AR)')
                                        ->required()
                                        ->rows(5)
                                        ->placeholder('Configration name (ar)')
                                        ->afterStateHydrated(fn ($state, $record, $component) => $component->state($record?->getTranslation('name', 'ar'))),
                                ],
                                'textedit' => [
                                    RichEditor::make('name.ar')
                                        ->label('Name (AR)')
                                        ->required()
                                        ->afterStateHydrated(fn ($state, $record, $component) => $component->state($record?->getTranslation('name', 'ar'))),
                                ],
                                default => [
                                    TextInput::make('name.ar')
                                        ->label('Name (AR)')
                                        ->required()
                                        ->type(match ($get('inputType')) {
                                            'number' => 'number',
                                            'email' => 'email',
                                            'url' => 'url',
                                            'date' => 'date',
                                            default => 'text',
                                        })
                                        ->placeholder(match ($get('inputType')) {
                                            'number' => 'Configration name (ar) [Numbers only]',
                                            'email' => 'Configration name (ar) [Email]',
                                            'url' => 'Configration name (ar) [URL]',
                                            default => 'Configration name (ar)',
                                        })
                                        ->afterStateHydrated(fn ($state, $record, $component) => $component->state($record?->getTranslation('name', 'ar'))),
                                ],
                            }),
                    ]),

                TextInput::make('slug')
                    ->required(),
                Textarea::make('note')
                    ->columnSpanFull(),

                Toggle::make('status')
                    ->required(),
            ]);
    }
}
