<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{

    public ?User $record;

    protected function getStats(): array
    {
        return [
            Stat::make('Name', $this->record->name),
            Stat::make('Posts', $this->record->posts()->count()),
            Stat::make('Comments', $this->record->comments()->count()),
        ];
    }
}
