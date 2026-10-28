<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Wichtigkeitsstufe für Ankündigungen auf der Startseite.
 */
enum AnnouncementLevel: string
{
    case Info = 'info';
    case Important = 'important';

    /**
     * Liefert die deutsche Bezeichnung der Stufe.
     */
    public function label(): string
    {
        return match ($this) {
            self::Info => 'Information',
            self::Important => 'Wichtig',
        };
    }

    /**
     * Liefert alle Stufen als Schlüssel-Wert-Array für Auswahlfelder.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::Info->value => self::Info->label(),
            self::Important->value => self::Important->label(),
        ];
    }
}
