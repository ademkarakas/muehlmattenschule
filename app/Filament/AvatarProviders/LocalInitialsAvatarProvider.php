<?php

declare(strict_types=1);

namespace App\Filament\AvatarProviders;

use App\Enums\UserRole;
use App\Models\User;
use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class LocalInitialsAvatarProvider implements AvatarProvider
{
    /**
     * Erzeugt einen DSGVO- und CSP-konformen lokalen SVG-Data-URI-Avatar (36×36, rx="10")
     * im Pastell-Farbschema von SupportCallSystem.Frontend (ohne externe Anfragen).
     */
    public function get(Model|Authenticatable $record): string
    {
        $rawName = $record instanceof User
            ? $record->displayName()
            : Filament::getNameForDefaultAvatar($record);

        $cleanName = trim((string) preg_replace('/\s*\([^)]*\)\s*/u', ' ', $rawName));

        $initials = str($cleanName !== '' ? $cleanName : 'MM')
            ->trim()
            ->explode(' ')
            ->filter(fn (string $segment): bool => filled($segment))
            ->map(function (string $segment): string {
                $letters = (string) preg_replace('/^[^\p{L}\p{N}]+/u', '', $segment);

                return filled($letters) ? mb_strtoupper(mb_substr($letters, 0, 1)) : '';
            })
            ->filter()
            ->take(2)
            ->join('');

        if ($initials === '') {
            $initials = 'MM';
        }

        [$bg, $fg] = $this->resolveRoleColors($record);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36" width="36" height="36" role="img" aria-label="{$initials}">
  <rect width="36" height="36" rx="10" fill="{$bg}" />
  <text x="50%" y="52%" dominant-baseline="central" text-anchor="middle" fill="{$fg}" font-family="system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif" font-size="13" font-weight="600" letter-spacing="0.4">{$initials}</text>
</svg>
SVG;

        return 'data:image/svg+xml;utf8,'.rawurlencode($svg);
    }

    /**
     * Liefert die Pastell-Hintergrund- und Textfarbe passend zur Benutzerrolle
     * (1:1 angelehnt an .avatar-admin / .avatar-leader / .avatar-user in SupportCallSystem.Frontend).
     *
     * @return array{0: string, 1: string}
     */
    private function resolveRoleColors(Model|Authenticatable $record): array
    {
        if (! $record instanceof User) {
            return ['#e8f4fd', '#0078d4'];
        }

        return match ($record->role) {
            UserRole::Admin => ['#fff0eb', '#e8590c'],
            UserRole::Schulleitung => ['#fff4e0', '#c07a00'],
            UserRole::Sekretariat => ['#e8f4fd', '#0078d4'],
            UserRole::Lehrkraft => ['#e6f6f4', '#029786'],
            default => ['#e8f4fd', '#0078d4'],
        };
    }
}
