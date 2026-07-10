<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\PageType;
use App\Models\Template;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),

                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('type')
                    ->label('Page Type')
                    ->options(fn () => PageType::query()->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($set) => $set('template_id', null)),

                Select::make('template_id')
                    ->label('Template')
                    ->options(function ($get) {
                        $typeId = $get('type');
                        if (! $typeId) {
                            return Template::query()->pluck('name', 'id')->toArray();
                        }

                        return Template::query()
                            ->where('page_type_id', $typeId)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->nullable(),

                TextInput::make('language')
                    ->maxLength(10)
                    ->default('en'),

                TextInput::make('copyright')
                    ->maxLength(255),

                Toggle::make('status')
                    ->label('Active')
                    ->default(true),

                Toggle::make('settings')
                    ->label('Has Custom Settings')
                    ->visible(false),
            ]);
    }
}
