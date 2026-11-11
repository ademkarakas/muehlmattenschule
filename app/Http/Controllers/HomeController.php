<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Event;
use App\Models\Post;
use App\Support\SeoData;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Rendert die EduPage-ähnliche Startseite mit Beiträgen, nächsten Terminen und neuesten Fotoalben.
     */
    public function __invoke(Request $request): Response
    {
        $paginated = Post::query()
            ->published()
            ->with(['category:id,name,slug,color', 'album.media', 'media'])
            ->orderByDesc('published_at')
            ->paginate(8)
            ->withQueryString();

        $posts = $paginated
            ->getCollection()
            ->map(function (Post $post): array {
                $ownGalleryCount = count($post->resolvedGalleryPhotos());
                $albumCount = $post->album !== null && $post->album->isPubliclyVisible()
                    ? $post->album->totalPhotoCount()
                    : 0;

                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => $post->excerpt,
                    'className' => $post->class_name,
                    'isFeatured' => $post->is_featured,
                    'publishedAt' => $post->published_at?->format('d.m.Y'),
                    'publishedAtIso' => $post->published_at?->toDateString(),
                    'coverUrl' => $post->getFirstMediaUrl('cover', 'card') ?: $post->getFirstMediaUrl('cover') ?: null,
                    'photosCount' => max($ownGalleryCount, $albumCount),
                    'category' => $post->category ? [
                        'name' => $post->category->name,
                        'slug' => $post->category->slug,
                        'color' => $post->category->color,
                    ] : null,
                ];
            })
            ->all();

        $upcomingEvents = Event::query()
            ->upcoming()
            ->limit(6)
            ->get()
            ->map(fn (Event $event): array => [
                'id' => $event->id,
                'title' => $event->title,
                'startsAtIso' => $event->starts_at->toIso8601String(),
                'day' => $event->starts_at->format('d'),
                'monthShort' => $event->starts_at->locale('de')->isoFormat('MMM'),
                'dateFormatted' => $event->starts_at->format('d.m.Y'),
                'timeFormatted' => $event->all_day ? 'Ganztägig' : $event->starts_at->format('H:i').' Uhr',
                'location' => $event->location,
                'className' => $event->class_name,
                'category' => $event->category ?? 'Termin',
                'icalUrl' => '/termine/'.$event->id.'/ical',
            ])
            ->all();

        $albums = Album::query()
            ->published()
            ->with('media')
            ->orderByDesc('event_date')
            ->limit(6)
            ->get()
            ->map(fn (Album $album): array => [
                'id' => $album->id,
                'title' => $album->title,
                'slug' => $album->slug,
                'className' => $album->class_name,
                'eventDate' => $album->event_date?->format('d.m.Y'),
                'photosCount' => $album->totalPhotoCount(),
                'coverUrl' => $album->coverThumbUrl(),
            ])
            ->all();

        return Inertia::render('home', [
            'posts' => $posts,
            'postsPagination' => [
                'currentPage' => $paginated->currentPage(),
                'lastPage' => $paginated->lastPage(),
                'total' => $paginated->total(),
                'prevUrl' => $paginated->previousPageUrl(),
                'nextUrl' => $paginated->nextPageUrl(),
            ],
            'upcomingEvents' => $upcomingEvents,
            'albums' => $albums,
            'seo' => SeoData::make([
                'title' => 'Startseite',
                'description' => 'Willkommen an der Mühlmattenschule – Grundschule Freiburg-Hochdorf mit Benzhausen. Aktuelle Nachrichten, Klassenaktivitäten, Termine, Elternbriefe und Ganztagsbetreuung.',
                'canonicalPath' => '/',
                'prevUrl' => $paginated->previousPageUrl(),
                'nextUrl' => $paginated->nextPageUrl(),
                'ogImage' => '/images/school/about-main.jpg',
                'ogImageAlt' => 'Schulgebäude und Pausenhof der Mühlmattenschule Freiburg-Hochdorf',
            ]),
        ]);
    }
}
