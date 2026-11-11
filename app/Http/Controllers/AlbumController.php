<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Album;
use App\Support\SeoData;
use Inertia\Inertia;
use Inertia\Response;

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
            ->orderByDesc('event_date')
            ->get()
            ->map(fn (Album $album): array => [
                'id' => $album->id,
                'title' => $album->title,
                'slug' => $album->slug,
                'className' => $album->class_name,
                'description' => $album->description,
                'eventDate' => $album->event_date?->format('d.m.Y'),
                'eventDateIso' => $album->event_date?->toDateString(),
                'photosCount' => $album->totalPhotoCount(),
                'coverUrl' => $album->coverThumbUrl(),
            ])
            ->all();

        return Inertia::render('albums/index', [
            'albums' => $albums,
            'seo' => SeoData::make([
                'title' => 'Fotogalerie & Eindrücke',
                'description' => 'Fotogalerie der Mühlmattenschule Freiburg-Hochdorf: Eindrücke von Ausflügen, Projekten, Festen und Aktivitäten unserer Schulklassen.',
                'canonicalPath' => '/galerie',
                'breadcrumbs' => [
                    ['label' => 'Startseite', 'href' => '/'],
                    ['label' => 'Fotogalerie', 'href' => null],
                ],
            ]),
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

        $photos = $album->resolvedPhotos();

        $coverUrl = $photos[0]['largeUrl'] ?? null;
        $canonicalPath = "/galerie/{$album->slug}";
        $canonicalUrl = SeoData::absoluteUrl($canonicalPath);
        $description = SeoData::cleanDescription(
            $album->description,
            "Fotoalbum „{$album->title}“ der Mühlmattenschule Freiburg-Hochdorf."
        );

        return Inertia::render('albums/show', [
            'album' => [
                'id' => $album->id,
                'title' => $album->title,
                'slug' => $album->slug,
                'className' => $album->class_name,
                'description' => $album->description,
                'eventDate' => $album->event_date?->format('d.m.Y'),
                'eventDateIso' => $album->event_date?->toDateString(),
                'photos' => $photos,
            ],
            'seo' => SeoData::make([
                'title' => "{$album->title} – Fotogalerie",
                'description' => $description,
                'canonicalPath' => $canonicalPath,
                'ogImage' => $coverUrl,
                'ogImageAlt' => $album->title,
                'breadcrumbs' => [
                    ['label' => 'Startseite', 'href' => '/'],
                    ['label' => 'Fotogalerie', 'href' => '/galerie'],
                    ['label' => $album->title, 'href' => null],
                ],
                'schemaNodes' => [
                    [
                        '@type' => 'ImageGallery',
                        '@id' => $canonicalUrl.'#gallery',
                        'name' => $album->title,
                        'description' => $description,
                        'url' => $canonicalUrl,
                        'datePublished' => ($album->event_date ?? $album->published_at ?? $album->created_at)?->toDateString(),
                        'about' => [
                            '@id' => url('/').'#school',
                        ],
                    ],
                ],
            ]),
        ]);
    }
}
