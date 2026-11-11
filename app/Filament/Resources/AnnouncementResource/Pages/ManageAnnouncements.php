<?php

declare(strict_types=1);

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use App\Models\Announcement;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAnnouncements extends ManageRecords
{
    protected static string $resource = AnnouncementResource::class;

    /**
     * Zeigt die aktiven und gesamten Ankündigungen dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        $active = Announcement::query()->where('is_active', true)->count();
        $total = Announcement::query()->count();

        return "{$active} aktive " . ($active === 1 ? 'Ankündigung' : 'Ankündigungen') . " auf der Startseite ({$total} insgesamt)";
    }

    /**
     * Kopfzeilen-Aktionen.
     *
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
