<?php

namespace App\Filament\Resources\SocialPlatforms\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SocialPlatformForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. Facebook, WhatsApp'),

                Select::make('icon')
                    ->label('Icon')
                    ->options([
                        // Brands
                        'fa-brands fa-facebook' => '<i class="fa-brands fa-facebook"></i> Facebook',
                        'fa-brands fa-facebook-messenger' => '<i class="fa-brands fa-facebook-messenger"></i> Messenger',
                        'fa-brands fa-instagram' => '<i class="fa-brands fa-instagram"></i> Instagram',
                        'fa-brands fa-tiktok' => '<i class="fa-brands fa-tiktok"></i> TikTok',
                        'fa-brands fa-linkedin' => '<i class="fa-brands fa-linkedin"></i> LinkedIn',
                        'fa-brands fa-whatsapp' => '<i class="fa-brands fa-whatsapp"></i> WhatsApp',
                        'fa-brands fa-youtube' => '<i class="fa-brands fa-youtube"></i> YouTube',
                        'fa-brands fa-twitter' => '<i class="fa-brands fa-twitter"></i> Twitter / X',
                        'fa-brands fa-telegram' => '<i class="fa-brands fa-telegram"></i> Telegram',
                        'fa-brands fa-snapchat' => '<i class="fa-brands fa-snapchat"></i> Snapchat',
                        'fa-brands fa-pinterest' => '<i class="fa-brands fa-pinterest"></i> Pinterest',
                        'fa-brands fa-reddit' => '<i class="fa-brands fa-reddit"></i> Reddit',
                        'fa-brands fa-github' => '<i class="fa-brands fa-github"></i> GitHub',
                        'fa-brands fa-discord' => '<i class="fa-brands fa-discord"></i> Discord',
                        'fa-brands fa-slack' => '<i class="fa-brands fa-slack"></i> Slack',
                        'fa-brands fa-twitch' => '<i class="fa-brands fa-twitch"></i> Twitch',
                        'fa-brands fa-vimeo' => '<i class="fa-brands fa-vimeo"></i> Vimeo',
                        'fa-brands fa-skype' => '<i class="fa-brands fa-skype"></i> Skype',
                        'fa-brands fa-threads' => '<i class="fa-brands fa-threads"></i> Threads',
                        // Solids & Regulars
                        'fa-solid fa-phone' => '<i class="fa-solid fa-phone"></i> Phone',
                        'fa-solid fa-envelope' => '<i class="fa-solid fa-envelope"></i> Email',
                        'fa-solid fa-globe' => '<i class="fa-solid fa-globe"></i> Website',
                        'fa-solid fa-location-dot' => '<i class="fa-solid fa-location-dot"></i> Location',
                        'fa-solid fa-user' => '<i class="fa-solid fa-user"></i> Profile / Personal',
                        'fa-solid fa-briefcase' => '<i class="fa-solid fa-briefcase"></i> Portfolio / Business',
                        'fa-solid fa-store' => '<i class="fa-solid fa-store"></i> Store',
                        'fa-solid fa-calendar-days' => '<i class="fa-solid fa-calendar-days"></i> Booking / Calendar',
                    ])
                    ->native(false)
                    ->allowHtml()
                    ->searchable()
                    ->required(),

                Select::make('type')
                    ->options([
                        'url' => 'URL',
                        'phone' => 'Phone',
                        'email' => 'Email',
                    ])
                    ->required(),

                TextInput::make('base_url')
                    ->maxLength(255)
                    ->placeholder('e.g. https://wa.me/ or https://facebook.com/'),

                ColorPicker::make('color')
                    ->nullable(),

                Toggle::make('status')
                    ->default(true)
                    ->required(),
            ]);
    }
}
