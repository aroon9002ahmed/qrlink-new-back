<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(4)
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Subscription Plan')
                            ->columnSpan(4)
                            ->schema([
                                TextEntry::make('no_subscription_notice')
                                    ->label('')
                                    ->getStateUsing(fn($record) => $record->activeSubscription
                                        ? null
                                        : '⚠️  This user does not have an active subscription.')
                                    ->color('danger')
                                    ->icon('heroicon-o-exclamation-triangle')
                                    ->hidden(fn($record) => $record->activeSubscription !== null),

                                Grid::make(3)
                                    ->hidden(fn($record) => $record->activeSubscription === null)
                                    ->schema([
                                        TextEntry::make('activeSubscription.subscriptionPlan.name')
                                            ->label('Active Plan')
                                            ->getStateUsing(fn($record) => $record->activeSubscription?->subscriptionPlan?->name ?? 'Free Plan')
                                            ->icon('heroicon-o-credit-card')
                                            ->weight('bold')
                                            ->color('primary')
                                            ->size('lg'),

                                        TextEntry::make('activeSubscription.status')
                                            ->label('Status')
                                            ->getStateUsing(fn($record) => $record->activeSubscription?->status ?? 'none')
                                            ->badge()
                                            ->color(fn(string $state): string => match ($state) {
                                                'active'    => 'success',
                                                'cancelled' => 'danger',
                                                'expired'   => 'warning',
                                                'pending'   => 'info',
                                                default     => 'gray',
                                            }),

                                        TextEntry::make('activeSubscription.billing_cycle')
                                            ->label('Billing Cycle')
                                            ->getStateUsing(fn($record) => $record->activeSubscription?->billing_cycle)
                                            ->badge()
                                            ->color('gray')
                                            ->placeholder('-'),

                                        TextEntry::make('activeSubscription.starts_at')
                                            ->label('Start Date')
                                            ->getStateUsing(fn($record) => $record->activeSubscription?->starts_at)
                                            ->dateTime()
                                            ->icon('heroicon-o-calendar')
                                            ->placeholder('-'),

                                        TextEntry::make('activeSubscription.ends_at')
                                            ->label('Expiry Date')
                                            ->getStateUsing(fn($record) => $record->activeSubscription?->ends_at)
                                            ->dateTime()
                                            ->icon('heroicon-o-clock')
                                            ->color(fn($record) => $record->activeSubscription?->ends_at?->isPast() ? 'danger' : 'success')
                                            ->placeholder('Never'),
                                    ]),
                            ]),
                        Section::make('Links Created')
                            ->columnSpan(1)
                            ->schema([
                                TextEntry::make('links_count')
                                    ->label('Total Links')
                                    ->getStateUsing(fn($record) => $record->links()->count())
                                    ->icon('heroicon-o-link')
                                    ->weight('bold')
                                    ->color('success')
                                    ->size('lg'),
                            ]),
                        Section::make('QR Codes Created')
                            ->columnSpan(1)
                            ->schema([
                                TextEntry::make('qrcodes_count')
                                    ->label('Total QR Codes')
                                    ->getStateUsing(fn($record) => $record->qrcodes()->count())
                                    ->icon('heroicon-o-qr-code')
                                    ->weight('bold')
                                    ->color('warning')
                                    ->size('lg'),
                            ]),
                        Section::make('Pages Created')
                            ->columnSpan(1)
                            ->schema([
                                TextEntry::make('pages_count')
                                    ->label('Total Pages')
                                    ->getStateUsing(fn($record) => $record->pages()->count())
                                    ->icon('heroicon-o-document-text')
                                    ->weight('bold')
                                    ->color('info')
                                    ->size('lg'),
                            ]),
                    ]),

                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('email_verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                IconEntry::make('status')
                    ->boolean(),
                TextEntry::make('oauth_provider')
                    ->placeholder('-'),
                TextEntry::make('oauth_uid')
                    ->placeholder('-'),
                TextEntry::make('last_login_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
