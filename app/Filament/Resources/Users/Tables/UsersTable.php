<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('status')
                    ->boolean(),

                TextColumn::make('activeSubscription.subscriptionPlan.name')
                    ->label('Current Plan')
                    ->getStateUsing(fn($record) => $record->activeSubscription
                        ? ($record->activeSubscription->subscriptionPlan?->name ?? 'Unknown Plan')
                        : 'No Subscription')
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        $state === 'No Subscription' => 'danger',
                        $state === 'Unknown Plan'    => 'warning',
                        default                      => 'primary',
                    })
                    ->icon(fn(string $state): string => $state === 'No Subscription'
                        ? 'heroicon-o-x-circle'
                        : 'heroicon-o-check-circle')
                    ->sortable()
                    ->searchable(query: function(Builder $query, string $search): Builder {
                        return $query->whereHas('activeSubscription.subscriptionPlan', fn($q) => $q->where('name', 'like', "%{$search}%"));
                    }),

                TextColumn::make('activeSubscription.ends_at')
                    ->label('Plan Expires')
                    ->getStateUsing(fn($record) => $record->activeSubscription?->ends_at)
                    ->dateTime()
                    ->color(fn($record): string => $record->activeSubscription?->ends_at?->isPast() ? 'danger' : 'success')
                    ->placeholder('No subscription')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('oauth_provider')
                    ->label('Provider')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'google' => 'Google',
                            'facebook' => 'Facebook',
                            default => 'Unknown',
                        };
                    })
                    ->searchable(),
                TextColumn::make('last_login_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email_verified_at')
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
