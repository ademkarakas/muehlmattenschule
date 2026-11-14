<?php

declare(strict_types=1);

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = PostResource::class;

    /**
     * Seitentitel des Formulars.
     */
    public function getTitle(): string
    {
        return 'Neuer Beitrag';
    }

    /**
     * Kurze Erklärung unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        return 'Verfassen Sie einen neuen Bericht oder eine aktuelle Mitteilung für die Schulwebsite.';
    }

    /**
     * Setzt den aktuell angemeldeten Benutzer automatisch als Verfasser des Beitrags.
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
