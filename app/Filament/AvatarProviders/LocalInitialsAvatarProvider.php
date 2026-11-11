<?php

declare(strict_types=1);

namespace App\Filament\AvatarProviders;

use App\Models\User;
use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class LocalInitialsAvatarProvider implements AvatarProvider
{
    /**
     * Erzeugt einen DSGVO- und CSP-konformen lokalen SVG-Data-URI-Avatar mit Initialen
     * im Farbschema der Mühlmattenschule (ohne externe Anfragen an ui-avatars.com).
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

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64" role="img" aria-label="{$initials}">
  <defs>
    <linearGradient id="av" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#029786" />
      <stop offset="100%" stop-color="#00544a" />
    </linearGradient>
  </defs>
  <rect width="64" height="64" rx="32" fill="url(#av)" />
  <circle cx="32" cy="32" r="30.5" fill="none" stroke="#ffffff" stroke-opacity="0.22" stroke-width="1.5" />
  <text x="50%" y="52%" dominant-baseline="central" text-anchor="middle" fill="#ffffff" font-family="system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif" font-size="24" font-weight="700" letter-spacing="0.5">{$initials}</text>
</svg>
SVG;

        return 'data:image/svg+xml;utf8,'.rawurlencode($svg);
    }
}

