<?php

declare(strict_types=1);

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Concerns\HasSupportCallListHeader;
use App\Filament\Resources\AnnouncementResource;
use App\Models\Announcement;
use Filament\Resources\Pages\ListRecords;

class ListAnnouncements extends ListRecords
{
    use HasSupportCallListHeader;

    protected static string $resource = AnnouncementResource::class;

    /**
     * Zeigt die aktiven und gesamten Ankündigungen dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        $active = Announcement::query()->where('is_active', true)->count();
        $total = Announcement::query()->count();

        return "{$active} aktive ".($active === 1 ? 'Ankündigung' : 'Ankündigungen')." auf der Startseite ({$total} insgesamt)";
    }
}
