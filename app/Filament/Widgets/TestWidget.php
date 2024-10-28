<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TestWidget extends BaseWidget
{

    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        
        $startDate = $startDate ? Carbon::parse($startDate): Carbon::now()->subDay(7);
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();

        return [
            Stat::make('Total Users', 
                    User::when($startDate, fn ($query) => $query->whereDate('created_at', '>', $startDate))
                        ->when($endDate, fn ($query) => $query->whereDate('created_at', '<', $endDate))
                        ->count()
                )
                ->description('All Users that have joined')
                ->descriptionIcon('heroicon-o-user-group', IconPosition::Before)
                ->chart([1, 3, 10, 8, 5, 10, 16])
                ->color('danger'),

            Stat::make('Admin Users', 
                    User::where('role', 'ADMIN')
                        ->when($startDate, fn ($query) => $query->whereDate('created_at', '>', $startDate))
                        ->when($endDate, fn ($query) => $query->whereDate('created_at', '<', $endDate))
                        ->count()
                )
                ->description('All Admin Users that have joined')
                ->descriptionIcon('heroicon-o-user-group', IconPosition::Before)
                ->chart([1, 3, 10, 18, 5, 10, 16])
                ->color('gray'),
            
            Stat::make('Editor Users', 
                    User::where('role', 'EDITOR')
                        ->when($startDate, fn ($query) => $query->whereDate('created_at', '>', $startDate))
                        ->when($endDate, fn ($query) => $query->whereDate('created_at', '<', $endDate))
                        ->count()
                )
                ->description('All Editor Users that have joined')
                ->descriptionIcon('heroicon-o-user-group', IconPosition::Before)
                ->chart([1, 3, 10, 8, 15, 10, 16])
                ->color('primary'),

            Stat::make('Normal Users', 
                    User::where('role', 'USER')
                        ->when($startDate, fn ($query) => $query->whereDate('created_at', '>', $startDate))
                        ->when($endDate, fn ($query) => $query->whereDate('created_at', '<', $endDate))
                        ->count()
                )
                ->description('All Normal Users that have joined')
                ->descriptionIcon('heroicon-o-user-group', IconPosition::Before)
                ->chart([1, 3, 10, 8, 15, 10, 16])
                ->color('warning')
        ];
    }
}
