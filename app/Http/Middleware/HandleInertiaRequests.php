<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Support\SeoData;
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
     * (Schulstammdaten, aktive Ankündigungen, dynamische Menüpunkte, Basis-SEO).
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $school = SeoData::schoolSettings();

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

        $hasMenuGroup = Schema::hasTable('pages') && Schema::hasColumn('pages', 'menu_group');
        $navigationPages = Schema::hasTable('pages')
            ? Page::query()
                ->published()
                ->orderBy('menu_order')
                ->get($hasMenuGroup ? ['title', 'slug', 'section', 'menu_group'] : ['title', 'slug', 'section'])
                ->groupBy('section')
                ->map(fn ($items): array => $items->map(fn (Page $page): array => [
                    'title' => $page->title,
                    'slug' => $page->slug,
                    'section' => $page->section,
                    'menuGroup' => $hasMenuGroup ? $page->menu_group : null,
                ])->values()->all())
                ->all()
            : [];

        $rubriken = [
            'totalPosts' => 0,
            'categories' => [],
            'classes' => [],
        ];

        if (Schema::hasTable('posts') && Schema::hasTable('categories')) {
            $hasPivot = Schema::hasTable('category_post');
            $alleCat = Category::query()->where('slug', 'alle-beitraege')->first();

            if ($alleCat !== null && $hasPivot) {
                $rubriken['totalPosts'] = $alleCat->allPosts()->published()->count();
            } else {
                $rubriken['totalPosts'] = Post::query()->published()->count();
            }

            $rubriken['categories'] = Category::query()
                ->where('slug', '!=', 'alle-beitraege')
                ->withCount([
                    ($hasPivot ? 'allPosts' : 'posts').' as posts_count' => fn ($q) => $q->published(),
                ])
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'color'])
                ->map(fn (Category $cat): array => [
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'color' => $cat->color,
                    'count' => (int) $cat->posts_count,
                ])
                ->all();

            $rubriken['classes'] = Post::query()
                ->published()
                ->whereNotNull('class_name')
                ->where('class_name', '!=', '')
                ->selectRaw('class_name, COUNT(*) as aggregate_count')
                ->groupBy('class_name')
                ->orderBy('class_name')
                ->get()
                ->map(fn ($row): array => [
                    'name' => (string) $row->class_name,
                    'count' => (int) $row->aggregate_count,
                ])
                ->all();
        }

        $konzepteProjekte = $navigationPages['konzepte-projekte'] ?? [];
        $angebote = $navigationPages['angebote'] ?? [];
        $schullebenLegacy = $navigationPages['schulleben'] ?? [];

        return [
            ...parent::share($request),
            'school' => $school,
            'maintenancePreview' => $school['maintenance_active'] && $request->user() !== null,
            'announcements' => $announcements,
            'navigationPages' => [
                'unsereSchule' => $navigationPages['unsere-schule'] ?? [],
                'konzepteProjekte' => $konzepteProjekte,
                'angebote' => $angebote,
                'schulleben' => [...$konzepteProjekte, ...$angebote, ...$schullebenLegacy],
            ],
            'rubriken' => $rubriken,
            'seo' => SeoData::make([
                'canonicalPath' => $request->getPathInfo(),
            ]),
        ];
    }
}
