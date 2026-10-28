<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Event;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Rendert die EduPage-ähnliche Startseite mit Beiträgen, nächsten Terminen und neuesten Fotoalben.
     */
    public function __invoke(): Response
    {
        $featuredQuery = Post::query()
            ->published()
            ->where('is_featured', true)
            ->with(['category:id,name,slug,color', 'media'])
            ->orderByDesc('published_at');

        $postModels = $featuredQuery->exists()
            ? $featuredQuery->get()
            : Post::query()
                ->published()
                ->with(['category:id,name,slug,color', 'media'])
                ->orderByDesc('published_at')
                ->limit(8)
                ->get();

        $posts = $postModels
            ->map(fn (Post $post): array => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'className' => $post->class_name,
                'isFeatured' => $post->is_featured,
                'publishedAt' => $post->published_at?->format('d.m.Y'),
                'coverUrl' => $post->getFirstMediaUrl('cover', 'card') ?: $post->getFirstMediaUrl('cover') ?: null,
                'category' => $post->category ? [
                    'name' => $post->category->name,
                    'slug' => $post->category->slug,
                    'color' => $post->category->color,
                ] : null,
            ])
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
            ])
            ->all();

        $albums = Album::query()
            ->published()
            ->with('media')
            ->withCount('media')
            ->orderByDesc('event_date')
            ->limit(6)
            ->get()
            ->map(fn (Album $album): array => [
                'id' => $album->id,
                'title' => $album->title,
                'slug' => $album->slug,
                'className' => $album->class_name,
                'eventDate' => $album->event_date?->format('d.m.Y'),
                'photosCount' => $album->media_count,
                'coverUrl' => $album->getFirstMediaUrl('photos', 'thumb') ?: $album->getFirstMediaUrl('photos') ?: null,
            ])
            ->all();

        return Inertia::render('home', [
            'posts' => $posts,
            'upcomingEvents' => $upcomingEvents,
            'albums' => $albums,
        ]);
    }
}
