<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use Filament\Support\Enums\Alignment;

/**
 * Zentrierte, am unteren Bildschirmrand haftende Formular-Aktionen (Speichern, Abbrechen, Löschen).
 */
trait HasCenteredStickyFormActions
{
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

