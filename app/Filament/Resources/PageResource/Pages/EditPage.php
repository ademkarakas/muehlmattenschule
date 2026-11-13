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
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class EditPage extends EditRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = PageResource::class;

    protected Width|string|null $maxContentWidth = Width::FiveExtraLarge;

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
     * Kopfzeilen-Aktionen: „← Zurück“ und „Auf Website ansehen“.
     *
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        /** @var Page $page */
        $page = $this->getRecord();

        return [
            $this->getBackToListHeaderAction(),
            Action::make('preview')
                ->label('Auf Website ansehen')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('gray')
                ->url('/'.$page->section.'/'.$page->slug, shouldOpenInNewTab: true),
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
