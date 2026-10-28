<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Benutzerrollen für das Admin-Panel der Mühlmattenschule.
 */
enum UserRole: string
{
    case Admin = 'admin';
    case Schulleitung = 'schulleitung';
    case Sekretariat = 'sekretariat';

    /**
     * Liefert die deutsche Bezeichnung der Rolle.
     */
    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Schulleitung => 'Schulleitung',
            self::Sekretariat => 'Sekretariat',
        };
    }

    /**
     * Liefert alle Rollen als Schlüssel-Wert-Array für Auswahlfelder.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::Admin->value => self::Admin->label(),
            self::Schulleitung->value => self::Schulleitung->label(),
            self::Sekretariat->value => self::Sekretariat->label(),
        ];
    }
}
