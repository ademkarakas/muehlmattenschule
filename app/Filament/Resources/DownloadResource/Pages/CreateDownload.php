<?php

declare(strict_types=1);

namespace App\Filament\Resources\DownloadResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\DownloadResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateDownload extends CreateRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = DownloadResource::class;

    protected Width|string|null $maxContentWidth = Width::FourExtraLarge;

    /**
     * Seitentitel des Formulars.
     */
    public function getTitle(): string
    {
        return 'Neues Dokument hochladen';
    }

    /**
     * Kurze Erklärung unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        return 'Stellen Sie einen Elternbrief, ein Formular oder eine Vorlage zum Herunterladen bereit.';
    }
}
