<?php

declare(strict_types=1);

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\CategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateCategory extends CreateRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = CategoryResource::class;

    protected Width|string|null $maxContentWidth = Width::FourExtraLarge;

    /**
     * Seitentitel des Formulars.
     */
    public function getTitle(): string
    {
        return 'Neue Kategorie';
    }

    /**
     * Kurze Erklärung unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        return 'Legen Sie eine Rubrik zur thematischen Gliederung der Schulbeiträge an.';
    }
}
