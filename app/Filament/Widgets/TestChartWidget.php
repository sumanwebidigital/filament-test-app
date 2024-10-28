<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class TestChartWidget extends ChartWidget
{

    use InteractsWithPageFilters;

    protected static ?string $heading = 'User Chart';

    // 1.
    // protected int | string | array $columnSpan = 2;

    // protected function getData(): array
    // {
    //     $data = Trend::model(User::class)
    //     ->between(
    //         start: now()->subDay(7),
    //         end: now(),
    //     )
    //     ->perDay()
    //     ->count();

    //     return [
    //         'datasets' => [
    //             [
    //                 'label' => 'No. of User created per day from last 7 days',
    //                 'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
    //             ],
    //         ],
    //         'labels' => $data->map(fn (TrendValue $value) => $value->date),
    //     ];

    //     // return [
    //     //     'datasets' => [
    //     //         [
    //     //             'label' => 'Blog posts created',
    //     //             'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
    //     //         ],
    //     //     ],
    //     //     'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    //     // ];
    // }

    // protected function getType(): string
    // {
    //     return 'line';
    // }


    // //2.
    // protected int | string | array $columnSpan = 1;

    // protected function getData(): array
    // {
    //     return [
    //         'labels' => [
    //             'Red',
    //             'Yellow',
    //             'Blue'
    //         ],
    //         'datasets' => [
    //             [
    //                 'label' => 'Blog posts created',
    //                 'data' => [10, 20, 30],
    //                 'backgroundColor' => [
    //                     'rgb(255, 99, 132)',
    //                     'rgb(255, 205, 86)',
    //                     'rgb(54, 162, 235)',
    //                 ],
    //                 'hoverOffset' => 10,
    //             ],
    //         ],
    //         'labels' => ["A", "B", "C"],
    //     ];
    // }

    // protected function getType(): string
    // {
    //     return 'doughnut';
    // }

    // 3. 
    protected function getData(): array
    {

        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        
        $startDate = $startDate ? Carbon::parse($startDate): Carbon::now()->subDay(7);
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();

        $data = Trend::model(User::class)
        ->between(
            start: $startDate,
            end: $endDate
        )
        ->perDay()
        ->count();

        return [
            'datasets' => [
                [
                    'label' => 'No. of User created per day from last 7 days',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

}
