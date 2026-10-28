<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Gruppierung des Schulpersonals auf der Kollegiums- und Verwaltungsseite.
 */
enum PersonGroup: string
{
    case Schulleitung = 'schulleitung';
    case Verwaltung = 'verwaltung';
    case Kollegium = 'kollegium';
    case Sozialarbeit = 'sozialarbeit';
    case Betreuung = 'betreuung';

    /**
     * Liefert die deutsche Bezeichnung der Gruppe.
     */
    public function label(): string
    {
        return match ($this) {
            self::Schulleitung => 'Schulleitung',
            self::Verwaltung => 'Sekretariat & Hausmeister',
            self::Kollegium => 'Lehrkräfte (Kollegium)',
            self::Sozialarbeit => 'Schulsozialarbeit',
            self::Betreuung => 'Schulkindbetreuung',
        };
    }

    /**
     * Liefert alle Gruppen als Schlüssel-Wert-Array für Auswahlfelder.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::Schulleitung->value => self::Schulleitung->label(),
            self::Verwaltung->value => self::Verwaltung->label(),
            self::Kollegium->value => self::Kollegium->label(),
            self::Sozialarbeit->value => self::Sozialarbeit->label(),
            self::Betreuung->value => self::Betreuung->label(),
        ];
    }
}
