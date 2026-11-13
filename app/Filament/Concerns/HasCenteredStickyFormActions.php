<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use Filament\Actions\Action;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;

/**
 * Einheitliches Layout für Hinzufügen- und Bearbeiten-Seiten im Admin-Panel:
 * - Kopfzeilen-Aktion „← Zurück“ zur Listenansicht (analog zu SupportCallSystem.Frontend)
 * - Zentrierte, am unteren Bildschirmrand haftende Formular-Aktionen (Speichern, Abbrechen, Löschen)
 */
trait HasCenteredStickyFormActions
{
    /**
     * Erstellt die „← Zurück“-Schaltfläche zur Übersichtsliste der jeweiligen Ressource.
     */
    protected function getBackToListHeaderAction(): Action
    {
        /** @var class-string<\Filament\Resources\Resource> $resource */
        $resource = static::getResource();

        return Action::make('backToList')
            ->label('Zurück')
            ->icon(Heroicon::OutlinedArrowLeft)
            ->color('gray')
            ->url($resource::getUrl('index'));
    }

    /**
     * Standard-Kopfzeilen-Aktionen für Create- und Edit-Seiten.
     *
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            $this->getBackToListHeaderAction(),
        ];
    }

    /**
     * Zentriert die Aktionsleiste.
     */
    public function getFormActionsAlignment(): string|Alignment
    {
        return Alignment::Center;
    }

    /**
     * Die Aktionsleiste bleibt beim Scrollen sichtbar.
     */
    public function areFormActionsSticky(): bool
    {
        return true;
    }
}
