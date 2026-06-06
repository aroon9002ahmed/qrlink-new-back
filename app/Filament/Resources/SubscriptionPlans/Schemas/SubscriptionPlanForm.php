<?php

namespace App\Filament\Resources\SubscriptionPlans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;


class SubscriptionPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Translations')
                    ->tabs([
                        Tab::make('English')
                            ->schema([
                                TextInput::make('name.en')
                                    ->label('Name (EN)')
                                    ->required()
                                    ->afterStateHydrated(fn ($state, $record, $component) => $component->state($record?->getTranslation('name', 'en'))),

                                TextInput::make('description.en')
                                    ->label('Description (EN)')
                                    ->afterStateHydrated(fn ($state, $record, $component) => $component->state($record?->getTranslation('description', 'en'))),
                            ]),
                        Tab::make('Arabic')
                            ->schema([
                                TextInput::make('name.ar')
                                    ->label('Name (AR)')
                                    ->required()
                                    ->afterStateHydrated(fn ($state, $record, $component) => $component->state($record?->getTranslation('name', 'ar'))),

                                TextInput::make('description.ar')
                                    ->label('Description (AR)')
                                    ->afterStateHydrated(fn ($state, $record, $component) => $component->state($record?->getTranslation('description', 'ar'))),
                            ]),
                    ])
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('price_monthly')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->minValue(0)
                    ->prefix('$'),
                TextInput::make('price_yearly')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->minValue(0)
                    ->prefix('$'),
                TextInput::make('max_links')
                    ->required()
                    ->numeric()
                    ->default(10),
                TextInput::make('max_qrcodes')
                    ->required()
                    ->numeric()
                    ->default(3),
                TextInput::make('max_pages')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('max_items')
                    ->required()
                    ->numeric()
                    ->default(20),
                Toggle::make('customization_templates')
                    ->required(),
                Toggle::make('restaurant_table')
                    ->required(),
                Toggle::make('delivery')
                    ->required(),
                Toggle::make('takeaway')
                    ->required(),
                Toggle::make('banners')
                    ->required(),
                Toggle::make('branches')
                    ->required(),
                Toggle::make('qr_code')
                    ->required(),
                Toggle::make('turn_off_Branding')
                    ->required(),
                Toggle::make('analytics')
                    ->required(),
                Toggle::make('priority_support')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
