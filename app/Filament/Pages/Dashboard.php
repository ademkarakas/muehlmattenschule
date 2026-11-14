<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Übersicht & Schnellzugriff';

    protected static ?string $navigationLabel = 'Dashboard';

    /**
     * Keine Breadcrumbs (1:1 wie in SupportCallSystem.Frontend).
     *
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [];
    }

    /**
     * Begrüßung im Stil von dashboard-landing.component.html:
     * „Willkommen, <Vorname>“.
     */
    public function getHeading(): string|Htmlable|null
    {
        $user = auth()->user();
        $firstName = e(str((string) ($user?->name ?? 'Redaktion'))->before(' ')->toString());

        return new HtmlString(
            '<span class="scs-title-row">'
            .'<span>Willkommen, <span style="color: var(--color-brand); font-weight: 600;">'.$firstName.'</span></span>'
            .'</span>'
        );
    }

    /**
     * Einheitlicher Untertitel unter der Seitenüberschrift (1:1 wie in dashboard-landing.component.html).
     */
    public function getSubheading(): string|Htmlable|null
    {
        return 'Hier ist die Übersicht Ihrer heutigen Kennzahlen und der neuesten Aktivitäten.';
    }

    /**
     * Keine Kopfzeilen-Aktionen auf dem Dashboard.
     *
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [];
    }
}
