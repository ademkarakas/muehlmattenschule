<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\PageResource;
use App\Models\Page;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = PageResource::class;

    /**
     * Seitentitel mit dem Namen der Schulseite.
     */
    public function getTitle(): string
    {
        /** @var Page $page */
        $page = $this->getRecord();

        return $page->title;
    }

    /**
     * Status, Hauptbereich und Kurz-URL dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        /** @var Page $page */
        $page = $this->getRecord();

        return collect([
            $page->status?->label(),
            $page->section,
            "/seiten/{$page->slug}",
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
