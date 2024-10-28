<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Widgets\CommentWidget;
use App\Filament\Widgets\TestChartWidget;
use App\Filament\Widgets\TestWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
           TestWidget::class,
        //    TestChartWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            CommentWidget::class,
        ];
    }
}
