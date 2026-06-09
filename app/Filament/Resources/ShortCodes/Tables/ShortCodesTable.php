<?php

namespace App\Filament\Resources\ShortCodes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ShortCodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label("#"),
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('codeable_type')
                    ->label('Type')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        \App\Models\Link::class => 'Link',
                        \App\Models\Qrcode::class => 'QR Code',
                        default => class_basename($state),
                    })
                    ->searchable(),
                TextColumn::make('codeable_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('clicks')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-user')
                    ->url(fn($record) => $record->user_id ? \App\Filament\Resources\Users\UserResource::getUrl('view', ['record' => $record->user_id]) : null),
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
                SelectFilter::make('codeable_type')
                    ->label('Type')
                    ->options([
                        \App\Models\Link::class => 'Link',
                        \App\Models\Qrcode::class => 'QR Code',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
