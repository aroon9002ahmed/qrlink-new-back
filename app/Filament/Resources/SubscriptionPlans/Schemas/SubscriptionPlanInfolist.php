<?php

namespace App\Filament\Resources\SubscriptionPlans\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class SubscriptionPlanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('max_links')
                    ->numeric(),
                TextEntry::make('max_qrcodes')
                    ->numeric(),
                TextEntry::make('sort_order')
                    ->numeric(),
                Section::make('Price')
                    ->columnSpanFull()
                    ->components([
                        Grid::make(2)
                            ->components([
                                TextEntry::make('price_monthly')
                                    ->numeric(),
                                TextEntry::make('price_yearly')
                                    ->numeric(),
                            ]),
                    ]),
                Section::make('Pages features')
                    ->columnSpanFull()
                    ->components([
                        Grid::make(4)
                            ->columnSpanFull()
                            ->schema([
                                TextEntry::make('max_pages')
                                    ->numeric(),
                                TextEntry::make('max_items')
                                    ->numeric(),
                                IconEntry::make('customization_templates')
                                    ->boolean(),
                                IconEntry::make('restaurant_table')
                                    ->boolean(),
                                IconEntry::make('delivery')
                                    ->boolean(),
                                IconEntry::make('takeaway')
                                    ->boolean(),
                                IconEntry::make('banners')
                                    ->boolean(),
                                IconEntry::make('branches')
                                    ->boolean(),
                                IconEntry::make('qr_code')
                                    ->boolean(),
                            ]),
                    ]),


                IconEntry::make('turn_off_Branding')
                    ->boolean(),
                IconEntry::make('analytics')
                    ->boolean(),
                IconEntry::make('priority_support')
                    ->boolean(),
                IconEntry::make('is_active')
                    ->boolean(),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),

                Section::make('Subscribed Users')
                    ->description(fn($record) => $record->subscriptions()->count() . ' user(s) subscribed to this plan')
                    ->icon('heroicon-o-users')
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('no_subscribers_notice')
                            ->label('')
                            ->getStateUsing(fn($record) => $record->subscriptions()->exists()
                                ? null
                                : '⚠️  No users are subscribed to this plan yet.')
                            ->color('warning')
                            ->icon('heroicon-o-exclamation-triangle')
                            ->hidden(fn($record) => $record->subscriptions()->exists()),

                        RepeatableEntry::make('subscriptions')
                            ->label('')
                            ->hidden(fn($record) => !$record->subscriptions()->exists())
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('User')
                                    ->icon('heroicon-o-user')
                                    ->weight('bold')
                                    ->url(fn($record) => \App\Filament\Resources\Users\UserResource::getUrl('view', ['record' => $record->user_id]))
                                    ->color('primary'),

                                TextEntry::make('starts_at')
                                    ->label('Start Date')
                                    ->dateTime()
                                    ->icon('heroicon-o-calendar'),

                                TextEntry::make('ends_at')
                                    ->label('Expiry Date')
                                    ->dateTime()
                                    ->icon('heroicon-o-clock')
                                    ->color(fn($record) => $record->ends_at?->isPast() ? 'danger' : 'success')
                                    ->placeholder('Never'),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
}
