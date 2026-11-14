<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\PublishStatus;
use App\Filament\Resources\AlbumResource;
use App\Filament\Resources\EventResource;
use App\Filament\Resources\PersonResource;
use App\Filament\Resources\PostResource;
use App\Models\Album;
use App\Models\Download;
use App\Models\Event;
use App\Models\Person;
use App\Models\Post;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SchoolOverviewStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    /**
     * Kennzahlen werden direkt mit der Seite gerendert (kein zusätzlicher Lazy-Load-Request).
     */
    protected static bool $isLazy = false;

    /**
     * Liefert die wichtigsten Kennzahlen für das Dashboard inklusive Schnellverlinkung.
     *
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $publishedPosts = Post::query()->published()->count();
        $draftPosts = Post::query()->where('status', PublishStatus::Draft)->count();
        $upcomingEvents = Event::query()->upcoming()->count();
        $publishedAlbums = Album::query()->published()->count();
        $activePeople = Person::query()->where('is_active', true)->count();
        $publishedDownloads = Download::query()->where('is_published', true)->count();

        $postDescription = $draftPosts > 0
            ? "Veröffentlicht · {$draftPosts} im Entwurf"
            : 'Aktuelles & Klassenaktivitäten';

        return [
            Stat::make('Veröffentlichte Beiträge', (string) $publishedPosts)
                ->description($postDescription)
                ->descriptionIcon(Heroicon::OutlinedNewspaper)
                ->color('primary')
                ->extraAttributes(['class' => 'scs-stat-card-1'])
                ->url(PostResource::getUrl('index')),

            Stat::make('Anstehende Termine', (string) $upcomingEvents)
                ->description('Im öffentlichen Schulkalender')
                ->descriptionIcon(Heroicon::OutlinedCalendarDays)
                ->color('warning')
                ->extraAttributes(['class' => 'scs-stat-card-2'])
                ->url(EventResource::getUrl('index')),

            Stat::make('Freigegebene Fotoalben', (string) $publishedAlbums)
                ->description('Mit bestätigter DSGVO-Freigabe')
                ->descriptionIcon(Heroicon::OutlinedPhoto)
                ->color('success')
                ->extraAttributes(['class' => 'scs-stat-card-3'])
                ->url(AlbumResource::getUrl('index')),

            Stat::make('Kollegium & Dokumente', "{$activePeople} / {$publishedDownloads}")
                ->description("{$activePeople} Teammitglieder · {$publishedDownloads} PDF-Downloads")
                ->descriptionIcon(Heroicon::OutlinedUserGroup)
                ->color('info')
                ->extraAttributes(['class' => 'scs-stat-card-4'])
                ->url(PersonResource::getUrl('index')),
        ];
    }
}
