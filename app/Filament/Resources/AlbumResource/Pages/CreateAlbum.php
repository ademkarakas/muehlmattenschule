<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlbumResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\AlbumResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateAlbum extends CreateRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = AlbumResource::class;

    protected Width|string|null $maxContentWidth = Width::FiveExtraLarge;

    /**
     * Seitentitel des Formulars.
     */
    public function getTitle(): string
    {
        return 'Neues Fotoalbum';
    }

    /**
     * Kurze Erklärung unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        return 'Erstellen Sie ein Fotoalbum für eine Schul- oder Klassenaktivität inklusive DSGVO-Freigabe.';
    }

    /**
     * Setzt den aktuell angemeldeten Benutzer automatisch als Ersteller des Fotoalbums.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['author_id'] = auth()->id();

        return $data;
    }
}
