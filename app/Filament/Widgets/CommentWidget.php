<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CommentWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('All Comments', Comment::count())
                ->description('All User Comments')
                ->descriptionIcon('heroicon-o-chat-bubble-oval-left-ellipsis', IconPosition::Before)
                ->chart([5, 3, 10, 18, 5, 12, 24])
                ->color('success')
                // ->descriptionColor('grey'),
        ];
    }
}
