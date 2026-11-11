<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\Event;
use App\Models\Page;
use App\Models\Person;
use App\Models\Post;
use App\Support\SeoData;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    /**
     * Durchsucht Beiträge, Schulseiten, Termine, Downloads und Kollegium nach dem Suchbegriff.
     */
    public function __invoke(Request $request): Response
    {
        $query = trim((string) $request->query('q', ''));
        $results = [
            'posts' => [],
            'pages' => [],
            'events' => [],
            'downloads' => [],
            'people' => [],
        ];

        if (mb_strlen($query) >= 2) {
            $like = '%'.$query.'%';

            $results['posts'] = Post::query()
                ->published()
                ->with('category:id,name,slug,color')
                ->where(function ($q) use ($like): void {
                    $q->where('title', 'like', $like)
                        ->orWhere('excerpt', 'like', $like)
                        ->orWhere('body', 'like', $like)
                        ->orWhere('class_name', 'like', $like);
                })
                ->orderByDesc('published_at')
                ->limit(15)
                ->get()
                ->map(fn (Post $post): array => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => $post->excerpt ?: SeoData::cleanDescription($post->body),
                    'className' => $post->class_name,
                    'publishedAt' => $post->published_at?->format('d.m.Y'),
                    'categoryName' => $post->category?->name,
                    'href' => '/aktuelles/'.$post->slug,
                ])
                ->all();

            $results['pages'] = Page::query()
                ->published()
                ->where(function ($q) use ($like): void {
                    $q->where('title', 'like', $like)
                        ->orWhere('summary', 'like', $like)
                        ->orWhere('content', 'like', $like);
                })
                ->orderBy('menu_order')
                ->limit(15)
                ->get()
                ->map(fn (Page $page): array => [
                    'id' => $page->id,
                    'title' => $page->title,
                    'summary' => $page->summary,
                    'section' => $page->section,
                    'href' => '/'.$page->section.'/'.$page->slug,
                ])
                ->all();

            $results['events'] = Event::query()
                ->upcoming()
                ->where(function ($q) use ($like): void {
                    $q->where('title', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhere('location', 'like', $like)
                        ->orWhere('class_name', 'like', $like);
                })
                ->limit(10)
                ->get()
                ->map(fn (Event $event): array => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'dateFormatted' => $event->starts_at->format('d.m.Y'),
                    'location' => $event->location,
                    'className' => $event->class_name,
                    'href' => '/termine',
                ])
                ->all();

            $results['downloads'] = Download::query()
                ->where('is_published', true)
                ->with('media')
                ->where(function ($q) use ($like): void {
                    $q->where('title', 'like', $like)
                        ->orWhere('category', 'like', $like)
                        ->orWhere('description', 'like', $like);
                })
                ->orderBy('sort_order')
                ->limit(10)
                ->get()
                ->map(fn (Download $dl): array => [
                    'id' => $dl->id,
                    'title' => $dl->title,
                    'category' => $dl->category,
                    'description' => $dl->description,
                    'fileUrl' => $dl->getFirstMediaUrl('file') ?: '/downloads',
                ])
                ->all();

            $results['people'] = Person::query()
                ->where('is_active', true)
                ->where(function ($q) use ($like): void {
                    $q->where('name', 'like', $like)
                        ->orWhere('role_title', 'like', $like)
                        ->orWhere('class_name', 'like', $like);
                })
                ->orderBy('sort_order')
                ->limit(10)
                ->get()
                ->map(fn (Person $person): array => [
                    'id' => $person->id,
                    'name' => $person->name,
                    'roleTitle' => $person->role_title,
                    'className' => $person->class_name,
                    'email' => $person->email,
                ])
                ->all();
        }

        $totalCount = count($results['posts'])
            + count($results['pages'])
            + count($results['events'])
            + count($results['downloads'])
            + count($results['people']);

        return Inertia::render('search/index', [
            'query' => $query,
            'totalCount' => $totalCount,
            'results' => $results,
            'seo' => SeoData::make([
                'title' => $query !== '' ? "Suche nach „{$query}“" : 'Suche auf dem Schulportal',
                'description' => 'Durchsuchen Sie Beiträge, Schulseiten, Termine, Formulare und Ansprechpartner der Mühlmattenschule Freiburg-Hochdorf.',
                'canonicalPath' => '/suche',
                'robots' => 'noindex, follow',
                'breadcrumbs' => [
                    ['label' => 'Startseite', 'href' => '/'],
                    ['label' => 'Suche', 'href' => null],
                ],
            ]),
        ]);
    }
}

