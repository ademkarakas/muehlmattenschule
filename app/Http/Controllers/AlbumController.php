<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Album;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AlbumController extends Controller
{
    /**
     * Zeigt die Übersicht aller freigegebenen Fotoalben.
     */
    public function index(): Response
    {
        $albums = Album::query()
            ->published()
            ->with('media')
            ->withCount('media')
            ->orderByDesc('event_date')
            ->get()
            ->map(fn (Album $album): array => [
                'id' => $album->id,
                'title' => $album->title,
                'slug' => $album->slug,
                'className' => $album->class_name,
                'description' => $album->description,
                'eventDate' => $album->event_date?->format('d.m.Y'),
                'photosCount' => $album->media_count,
                'coverUrl' => $album->getFirstMediaUrl('photos', 'thumb') ?: $album->getFirstMediaUrl('photos') ?: null,
            ])
            ->all();

        return Inertia::render('albums/index', [
            'albums' => $albums,
        ]);
    }

    /**
     * Zeigt ein einzelnes Fotoalbum mit allen Bildern für die Lightbox-Ansicht.
     */
    public function show(string $slug): Response
    {
        $album = Album::query()
            ->published()
            ->with('media')
            ->where('slug', $slug)
            ->firstOrFail();

        $photos = $album->getMedia('photos')->map(fn (Media $media): array => [
            'id' => $media->id,
            'thumbUrl' => $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl(),
            'largeUrl' => $media->hasGeneratedConversion('large') ? $media->getUrl('large') : $media->getUrl(),
            'alt' => $album->title,
        ])->values()->all();

        return Inertia::render('albums/show', [
            'album' => [
                'id' => $album->id,
                'title' => $album->title,
                'slug' => $album->slug,
                'className' => $album->class_name,
                'description' => $album->description,
                'eventDate' => $album->event_date?->format('d.m.Y'),
                'photos' => $photos,
            ],
        ]);
    }
}
