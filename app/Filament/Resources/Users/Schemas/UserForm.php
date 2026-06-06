<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\SubscriptionPlan;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->disabled(),
                TextEntry::make('email_verified_at'),
                TextInput::make('phone')
                    ->tel(),


                Section::make('Subscription Plan')
                    ->collapsed()
                    ->description('Manage the user\'s active subscription.')
                    ->icon('heroicon-o-credit-card')
                    ->schema([
                        Repeater::make('subscriptions')
                            ->relationship('subscriptions', fn($query) => $query->orderByDesc('starts_at'))
                            ->label('')
                            ->addActionLabel('Add Subscription')
                            ->maxItems(1)
                            ->collapsible()
                            ->cloneable(false)
                            ->schema([
                                Select::make('subscription_plan_id')
                                    ->label('Plan Name')
                                    ->options(
                                        SubscriptionPlan::query()
                                            ->where('is_active', true)
                                            ->orderBy('sort_order')
                                            ->pluck('name', 'id')
                                            ->toArray()
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpanFull(),

                                Select::make('billing_cycle')
                                    ->label('Billing Cycle')
                                    ->options([
                                        'monthly' => 'Monthly',
                                        'yearly'  => 'Yearly',
                                    ])
                                    ->required(),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'active'    => 'Active',
                                        'cancelled' => 'Cancelled',
                                        'expired'   => 'Expired',
                                        'pending'   => 'Pending',
                                    ])
                                    ->required()
                                    ->default('active'),

                                DateTimePicker::make('starts_at')
                                    ->label('Start Date')
                                    ->required()
                                    ->default(now()),

                                DateTimePicker::make('ends_at')
                                    ->label('End Date')
                                    ->nullable()
                                    ->after('starts_at'),

                                TextInput::make('amount_paid')
                                    ->label('Amount Paid')
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0)
                                    ->default(0)
                                    ->required(),

                                Select::make('payment_method')
                                    ->label('Payment Method')
                                    ->options([
                                        'free'         => 'Free',
                                        'stripe'       => 'Stripe',
                                        'paypal'       => 'PayPal',
                                        'bank_transfer' => 'Bank Transfer',
                                        'manual'       => 'Manual / Admin',
                                    ])
                                    ->nullable(),
                            ])
                            ->columns(2),
                    ]),

                Section::make('Change Password')
                    ->description('Leave blank if you do not want to change the user\'s password.')
                    ->schema([
                        TextInput::make('password')
                            ->label('New Password')
                            ->password()
                            ->confirmed()
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $operation): bool => $operation === 'create'),
                        TextInput::make('password_confirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->dehydrated(false)
                            ->required(fn(string $operation, callable $get): bool => $operation === 'create' || filled($get('password'))),
                    ]),
                Toggle::make('status')
                    ->required(),
                TextInput::make('oauth_provider')->disabled(),
            ]);
    }
}
