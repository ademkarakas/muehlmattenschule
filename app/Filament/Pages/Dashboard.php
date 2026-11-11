<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Übersicht & Schnellzugriff';

    protected static ?string $navigationLabel = 'Dashboard';

    /**
     * Überschrift und Untertitel stehen nebeneinander in einer Zeile.
     */
    public function getHeading(): string|Htmlable
    {
        $title = e(static::$title);
        $subtitle = e('Redaktions- und Verwaltungszentrale der Mühlmattenschule Freiburg-Hochdorf');

        return new HtmlString(
            '<span style="display:flex;flex-wrap:wrap;align-items:baseline;column-gap:1rem;row-gap:0.15rem;">'
            ."<span>{$title}</span>"
            ."<span style=\"font-size:0.95rem;font-weight:400;color:#64748b;letter-spacing:0;\">{$subtitle}</span>"
            .'</span>'
        );
    }

    /**
     * Kein separater Untertitel mehr, er ist Teil der Überschrift.
     */
    public function getSubheading(): string|Htmlable|null
    {
        return null;
    }
}
