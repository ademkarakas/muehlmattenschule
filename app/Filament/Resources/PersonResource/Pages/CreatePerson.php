<?php

declare(strict_types=1);

namespace App\Filament\Resources\PersonResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\PersonResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreatePerson extends CreateRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = PersonResource::class;

    protected Width|string|null $maxContentWidth = Width::FourExtraLarge;

    /**
     * Seitentitel des Formulars.
     */
    public function getTitle(): string
    {
        return 'Neue Person im Schulteam';
    }

    /**
     * Kurze Erklärung unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        return 'Tragen Sie eine Person für Schulleitung, Kollegium, Schulsozialarbeit oder Betreuung ein.';
    }
}
