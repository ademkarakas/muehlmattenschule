<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Announcement;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * Das Root-Blade-Template für Inertia-Antworten.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Bestimmt die aktuelle Asset-Version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Definiert die auf allen öffentlichen Seiten gemeinsam verfügbaren Props
     * (Schulstammdaten, aktive Ankündigungen, dynamische Menüpunkte).
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        /** @var array<string, string> $defaultSchool */
        $defaultSchool = [
            'name' => 'Mühlmattenschule',
            'subtitle' => 'Grundschule Freiburg-Hochdorf',
            'street' => 'Zu den Mühlmatten 8',
            'zip_city' => '79108 Freiburg im Breisgau',
            'phone' => '07665 2232',
            'email' => 'sekretariat.mmsvn@freiburger-schulen.bwl.de',
            'office_hours' => 'Mo, Mi, Fr 7:30 – 12:00 Uhr',
            'welcome_title' => 'Willkommen an der Mühlmattenschule',
            'welcome_text' => 'Die Grundschule für den Freiburger Stadtteil Hochdorf mit Benzhausen – ein Ort zum Lernen, Wachsen und Entdecken.',
        ];

        $savedSchool = Schema::hasTable('settings')
            ? Setting::getValue('school', [])
            : [];

        $school = array_merge($defaultSchool, is_array($savedSchool) ? $savedSchool : []);

        $announcements = Schema::hasTable('announcements')
            ? Announcement::query()
                ->currentlyVisible()
                ->orderByDesc('level')
                ->latest()
                ->get(['id', 'text', 'level', 'link_url', 'link_label'])
                ->map(fn (Announcement $item): array => [
                    'id' => $item->id,
                    'text' => $item->text,
                    'level' => $item->level->value,
                    'linkUrl' => $item->link_url,
                    'linkLabel' => $item->link_label,
                ])
                ->all()
            : [];

        $navigationPages = Schema::hasTable('pages')
            ? Page::query()
                ->published()
                ->orderBy('menu_order')
                ->get(['title', 'slug', 'section'])
                ->groupBy('section')
                ->map(fn ($items): array => $items->map(fn (Page $page): array => [
                    'title' => $page->title,
                    'slug' => $page->slug,
                ])->values()->all())
                ->all()
            : [];

        return [
            ...parent::share($request),
            'school' => $school,
            'announcements' => $announcements,
            'navigationPages' => [
                'unsereSchule' => $navigationPages['unsere-schule'] ?? [],
                'schulleben' => $navigationPages['schulleben'] ?? [],
            ],
        ];
    }
}
