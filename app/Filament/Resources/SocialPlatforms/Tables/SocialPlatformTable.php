<?php

namespace App\Filament\Resources\SocialPlatforms\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SocialPlatformTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('icon')
                    ->label('Icon')
                    ->html()
                    ->formatStateUsing(fn ($state) => $state ? "<i class='{$state}' style='font-size: 1.1rem; width: 1.5rem; display: inline-block;'></i> <span>{$state}</span>" : '')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->sortable(),

                TextColumn::make('base_url')
                    ->label('Base URL')
                    ->toggleable(isToggledHiddenByDefault: true),

                ColorColumn::make('color')
                    ->label('Color')
                    ->copyable()
                    ->copyMessage('Color code copied'),

                IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
