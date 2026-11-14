<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = EventResource::class;

    /**
     * Seitentitel des Formulars.
     */
    public function getTitle(): string
    {
        return 'Neuer Schultermin';
    }

    /**
     * Kurze Erklärung unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        return 'Tragen Sie eine Veranstaltung, einen Projekttag oder einen Ferientermin für den Schulkalender ein.';
    }
}
