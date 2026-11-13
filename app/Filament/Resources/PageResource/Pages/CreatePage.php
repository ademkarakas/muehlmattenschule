<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\PageResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreatePage extends CreateRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = PageResource::class;

    protected Width|string|null $maxContentWidth = Width::FiveExtraLarge;

    /**
     * Seitentitel des Formulars.
     */
    public function getTitle(): string
    {
        return 'Neue Schulseite';
    }

    /**
     * Kurze Erklärung unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        return 'Legen Sie eine feste Informationsseite mit flexiblen Inhaltsblöcken für das Hauptmenü an.';
    }
}
