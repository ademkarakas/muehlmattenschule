<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Übersicht & Schnellzugriff';

    protected static ?string $navigationLabel = 'Dashboard';

    /**
     * Einheitlicher Untertitel unter der Seitenüberschrift (analog zu allen Listen- und Formularseiten).
     */
    public function getSubheading(): string|Htmlable|null
    {
        return 'Redaktions- und Verwaltungszentrale der Mühlmattenschule Freiburg-Hochdorf';
    }
}
