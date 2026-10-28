<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Album;
use App\Models\Event;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SchoolOverviewStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    /**
     * Liefert die wichtigsten Kennzahlen für das Dashboard.
     *
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Veröffentlichte Beiträge', (string) Post::query()->published()->count())
                ->description('Aktuelles & Klassenaktivitäten')
                ->color('primary'),
            Stat::make('Anstehende Termine', (string) Event::query()->upcoming()->count())
                ->description('Veröffentlichte Schultermine')
                ->color('warning'),
            Stat::make('Freigegebene Fotoalben', (string) Album::query()->published()->count())
                ->description('Mit bestätigter DSGVO-Einwilligung')
                ->color('success'),
        ];
    }
}
