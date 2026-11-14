<?php

declare(strict_types=1);

namespace App\Filament\Resources\DownloadResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\DownloadResource;
use App\Models\Download;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDownload extends EditRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = DownloadResource::class;

    /**
     * Seitentitel mit dem Namen des Dokuments.
     */
    public function getTitle(): string
    {
        /** @var Download $download */
        $download = $this->getRecord();

        return $download->title;
    }

    /**
     * Bereich und Veröffentlichungsstatus dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        /** @var Download $download */
        $download = $this->getRecord();

        return collect([
            $download->category,
            $download->is_published ? 'Veröffentlicht' : 'Entwurf / verborgen',
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
