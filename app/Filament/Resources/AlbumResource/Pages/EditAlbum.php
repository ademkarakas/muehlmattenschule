<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlbumResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\AlbumResource;
use App\Models\Album;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAlbum extends EditRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = AlbumResource::class;

    /**
     * Seitentitel mit dem Namen des Fotoalbums.
     */
    public function getTitle(): string
    {
        /** @var Album $album */
        $album = $this->getRecord();

        return $album->title;
    }

    /**
     * Status, Klasse und Datum dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        /** @var Album $album */
        $album = $this->getRecord();

        return collect([
            $album->status?->label(),
            $album->class_name,
            $album->event_date?->format('d.m.Y'),
        ])->filter()->implode(' · ');
    }

    /**
     * Speichern, Abbrechen und Löschen nebeneinander in einer zentrierten Leiste.
     *
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
            DeleteAction::make(),
        ];
    }
}
