<?php

declare(strict_types=1);

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\AnnouncementResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateAnnouncement extends CreateRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = AnnouncementResource::class;

    protected Width|string|null $maxContentWidth = Width::FourExtraLarge;

    /**
     * Seitentitel des Formulars.
     */
    public function getTitle(): string
    {
        return 'Neue Ankündigung';
    }

    /**
     * Kurze Erklärung unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        return 'Erstellen Sie eine Mitteilung für das Hinweisbanner auf der Startseite.';
    }
}
