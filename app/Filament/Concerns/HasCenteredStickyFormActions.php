<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use Filament\Support\Enums\Alignment;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

/**
 * Einheitliches Layout für Hinzufügen- und Bearbeiten-Seiten:
 * - Sticky .top-bar oben: Links [← Zurück] | Mitte [Titel + Untertitel]
 * - Formular-Aktionen (Speichern, Abbrechen, Löschen) zentriert unter dem Formular
 */
trait HasCenteredStickyFormActions
{
    /**
     * Keine Breadcrumbs über der .top-bar.
     *
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [];
    }

    /**
     * Rendert die linke und mittlere Zone der .top-bar ([← Zurück] + [Titel & Untertitel]).
     */
    public function getHeading(): string|Htmlable
    {
        /** @var class-string<\Filament\Resources\Resource> $resource */
        $resource = static::getResource();

        $backUrl = e($resource::getUrl('index'));
        $title = e($this->getTitle());
        $subheading = $this->getSubheading();
        $subtitleHtml = filled($subheading)
            ? '<span class="scs-top-bar-subtitle">'.e((string) $subheading).'</span>'
            : '';

        return new HtmlString(
            '<span class="scs-top-bar-lead">'
            .'<a href="'.$backUrl.'" class="scs-back-btn">'
            .'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="scs-back-icon" aria-hidden="true"><path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" /></svg>'
            .'<span>Zurück</span>'
            .'</a>'
            .'<span class="scs-top-bar-titles">'
            .'<span class="scs-top-bar-title">'.$title.'</span>'
            .$subtitleHtml
            .'</span>'
            .'</span>'
        );
    }

    /**
     * Zentriert die Aktionsleiste unter dem Formular.
     */
    public function getFormActionsAlignment(): string|Alignment
    {
        return Alignment::Center;
    }

    /**
     * Aktionsleiste bleibt am Formularende statt schwebend am unteren Rand zu kleben.
     */
    public function areFormActionsSticky(): bool
    {
        return false;
    }
}
