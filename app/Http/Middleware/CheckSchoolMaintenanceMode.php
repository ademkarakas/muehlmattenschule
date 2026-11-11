<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\SeoData;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSchoolMaintenanceMode
{
    /**
     * Prüft, ob der über das Admin-Panel gesteuerte Wartungsmodus aktiv ist.
     * Gäste erhalten eine informative HTTP-503-Wartungsseite mit Sekretariatskontakt;
     * angemeldete Mitarbeitende können die Website weiterhin normal zur Vorschau nutzen.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->is('admin', 'admin/*', 'livewire', 'livewire/*', 'up')
        ) {
            return $next($request);
        }

        $school = SeoData::schoolSettings();

        if (! $school['maintenance_active']) {
            return $next($request);
        }

        // Angemeldete Mitarbeitende (Admin, Schulleitung, Sekretariat, Lehrkraft) dürfen die Seite als Vorschau sehen
        // oder mit ?wartungsmodus-vorschau=1 gezielt die 503-Wartungsseite prüfen.
        if ($request->user() !== null) {
            if ($request->boolean('wartungsmodus-vorschau')) {
                return response()
                    ->view('errors.503', [
                        'school' => $school,
                        'isPreview' => true,
                    ], 200)
                    ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            }

            return $next($request);
        }

        return response()
            ->view('errors.503', ['school' => $school], 503)
            ->header('Retry-After', '3600')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
}
