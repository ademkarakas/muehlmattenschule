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
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class EditAlbum extends EditRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = AlbumResource::class;

    protected Width|string|null $maxContentWidth = Width::FiveExtraLarge;

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
     * Kopfzeilen-Aktionen: „← Zurück“ und „Auf Website ansehen“.
     *
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        /** @var Album $album */
        $album = $this->getRecord();

        return [
            $this->getBackToListHeaderAction(),
            Action::make('preview')
                ->label('Auf Website ansehen')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('gray')
                ->url('/galerie/'.$album->slug, shouldOpenInNewTab: true),
        ];
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
