<?php

namespace App\Filament\Widgets;

use App\Models\Page;
use App\Models\Link;
use App\Models\Qrcode;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -5;

    protected function getStats(): array
    {

        return [
            Stat::make('Total Pages', Page::count())
                ->description('Total pages created in the system')
                ->icon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Total Links', Link::count())
                ->description('Total links created in the system')
                ->icon('heroicon-m-link')
                ->color('info'),

            Stat::make('Total QR Codes', Qrcode::count())
                ->description('Total QR codes generated in the system')
                ->icon('heroicon-m-qr-code')
                ->color('primary'),

            Stat::make('Total users', User::count())
                ->description('Total users in the system')
                ->icon('heroicon-m-user-group')
                ->color('warning'),
        ];
    }
}
