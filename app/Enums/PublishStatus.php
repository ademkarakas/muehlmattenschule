<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Veröffentlichungsstatus für Beiträge, Fotoalben und Seiten.
 */
enum PublishStatus: string
{
    case Draft = 'draft';
    case Published = 'published';

    /**
     * Liefert die deutsche Bezeichnung des Status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Entwurf',
            self::Published => 'Veröffentlicht',
        };
    }

    /**
     * Liefert alle Statuswerte als Schlüssel-Wert-Array für Auswahlfelder.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::Draft->value => self::Draft->label(),
            self::Published->value => self::Published->label(),
        ];
    }
}

