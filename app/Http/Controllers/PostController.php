<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Support\SeoData;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    /**
     * Zeigt die gefilterte und paginierte Übersicht aller Beiträge & Klassenaktivitäten.
     */
    public function index(Request $request): Response
    {
        $categorySlug = $request->string('kategorie')->trim()->value();
        $classFilter = $request->string('klasse')->trim()->value();

        $query = Post::query()
            ->published()
            ->with(['category:id,name,slug,color', 'album.media', 'media'])
            ->orderByDesc('published_at');

        if ($categorySlug !== '') {
            $query->where(function ($outer) use ($categorySlug): void {
                $outer->whereHas('category', fn ($q) => $q->where('slug', $categorySlug))
                    ->orWhereHas('categories', fn ($q) => $q->where('slug', $categorySlug));
            });
        }

        if ($classFilter !== '') {
            $query->where('class_name', $classFilter);
        }

        $paginated = $query->paginate(9)->withQueryString();

        $items = $paginated->getCollection()->map(function (Post $post): array {
            $ownGalleryCount = count($post->resolvedGalleryPhotos());
            $albumCount = $post->album !== null && $post->album->isPubliclyVisible()
                ? $post->album->totalPhotoCount()
                : 0;

            $firstInlineImg = null;
            if (preg_match('#<img\b[^>]*src="(/images/school/[^"]+)"#i', (string) $post->body, $imgMatch)) {
                $firstInlineImg = $imgMatch[1];
            }
            $firstGalleryImg = is_array($post->gallery_images) && isset($post->gallery_images[0]) && is_string($post->gallery_images[0])
                ? $post->gallery_images[0]
                : null;

            return [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'className' => $post->class_name,
                'isFeatured' => $post->is_featured,
                'publishedAt' => $post->published_at?->locale('de')->isoFormat('D. MMMM YYYY'),
                'publishedAtIso' => $post->published_at?->toDateString(),
                'coverUrl' => $post->getFirstMediaUrl('cover')
                    ?: $firstGalleryImg
                    ?: $firstInlineImg,
                'photosCount' => max($ownGalleryCount, $albumCount),
                'category' => $post->category ? [
                    'name' => $post->category->name,
                    'slug' => $post->category->slug,
                    'color' => $post->category->color,
                ] : null,
            ];
        })->all();

        $categories = Category::query()
            ->where('slug', '!=', 'alle-beitraege')
            ->orderBy('name')
            ->get(['name', 'slug', 'color'])
            ->all();

        $availableClasses = Post::query()
            ->published()
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name')
            ->all();

        $seoTitle = 'Aktuelles & Klassenaktivitäten';
        if ($classFilter !== '') {
            $seoTitle = "Beiträge aus {$classFilter} – Aktuelles";
        } elseif ($categorySlug !== '') {
            $activeCat = collect($categories)->firstWhere('slug', $categorySlug);
            if ($activeCat !== null) {
                $seoTitle = "{$activeCat->name} – Aktuelles & Klassen";
            }
        }

        if ($paginated->currentPage() > 1) {
            $seoTitle .= " (Seite {$paginated->currentPage()})";
        }

        return Inertia::render('posts/index', [
            'posts' => $items,
            'pagination' => [
                'currentPage' => $paginated->currentPage(),
                'lastPage' => $paginated->lastPage(),
                'prevUrl' => $paginated->previousPageUrl(),
                'nextUrl' => $paginated->nextPageUrl(),
            ],
            'categories' => $categories,
            'availableClasses' => $availableClasses,
            'activeCategory' => $categorySlug !== '' ? $categorySlug : null,
            'activeClass' => $classFilter !== '' ? $classFilter : null,
            'seo' => SeoData::make([
                'title' => $seoTitle,
                'description' => 'Aktuelle Berichte aus dem Schulalltag, Ausflüge, Projekte, Feste und Neuigkeiten aus den Klassen der Mühlmattenschule Freiburg-Hochdorf.',
                'canonicalPath' => '/aktuelles',
                'prevUrl' => $paginated->previousPageUrl(),
                'nextUrl' => $paginated->nextPageUrl(),
                'breadcrumbs' => [
                    ['label' => 'Startseite', 'href' => '/'],
                    ['label' => 'Nachrichten & Klassen', 'href' => null],
                ],
            ]),
        ]);
    }

    /**
     * Zeigt die Detailansicht eines einzelnen Beitrags inklusive verknüpftem Fotoalbum.
     */
    public function show(string $slug): Response
    {
        $post = Post::query()
            ->published()
            ->with(['category:id,name,slug,color', 'author:id,name', 'album.media', 'media'])
            ->where('slug', $slug)
            ->firstOrFail();

        $linkedAlbum = null;
        if ($post->album !== null && $post->album->isPubliclyVisible()) {
            $linkedAlbum = [
                'title' => $post->album->title,
                'slug' => $post->album->slug,
                'photosCount' => $post->album->totalPhotoCount(),
            ];
        }

        $galleryPhotos = $post->resolvedGalleryPhotos();

        $coverUrl = $post->getFirstMediaUrl('cover', 'hero') ?: $post->getFirstMediaUrl('cover') ?: null;
        $canonicalPath = "/aktuelles/{$post->slug}";
        $canonicalUrl = SeoData::absoluteUrl($canonicalPath);
        $description = SeoData::cleanDescription($post->excerpt ?: $post->body);
        $publishedIso = ($post->published_at ?? $post->created_at)?->toIso8601String();
        $modifiedIso = ($post->updated_at ?? $post->published_at ?? $post->created_at)?->toIso8601String();
        $imageUrl = SeoData::absoluteUrl($coverUrl ?? '/images/school/about-main.jpg');

        $isAuthenticatedStaff = request()->user() !== null;
        $authorDisplayName = $post->author?->name ?? 'mmsadmin';

        return Inertia::render('posts/show', [
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'body' => $post->body,
                'className' => $post->class_name,
                'publishedAt' => $post->published_at?->locale('de')->isoFormat('D. MMMM YYYY'),
                'publishedAtIso' => $post->published_at?->toDateString(),
                'authorName' => $isAuthenticatedStaff ? $authorDisplayName : null,
                'adminMeta' => $isAuthenticatedStaff ? [
                    'authorName' => $authorDisplayName,
                    'publishedAtFull' => $post->published_at?->format('d.m.Y H:i'),
                    'updatedAtFull' => $post->updated_at?->format('d.m.Y H:i'),
                    'editUrl' => "/admin/posts/{$post->id}/edit",
                ] : null,
                'coverUrl' => $coverUrl,
                'category' => $post->category ? [
                    'name' => $post->category->name,
                    'slug' => $post->category->slug,
                    'color' => $post->category->color,
                ] : null,
                'linkedAlbum' => $linkedAlbum,
                'galleryPhotos' => $galleryPhotos,
            ],
            'seo' => SeoData::make([
                'title' => $post->title,
                'description' => $description,
                'canonicalPath' => $canonicalPath,
                'ogType' => 'article',
                'ogImage' => $coverUrl,
                'ogImageAlt' => $post->title,
                'publishedTime' => $publishedIso,
                'modifiedTime' => $modifiedIso,
                'section' => $post->category?->name ?? $post->class_name ?? 'Schulleben',
                'breadcrumbs' => [
                    ['label' => 'Startseite', 'href' => '/'],
                    ['label' => 'Nachrichten & Klassen', 'href' => '/aktuelles'],
                    ['label' => $post->title, 'href' => null],
                ],
                'schemaNodes' => [
                    [
                        '@type' => 'NewsArticle',
                        '@id' => $canonicalUrl.'#article',
                        'mainEntityOfPage' => [
                            '@type' => 'WebPage',
                            '@id' => $canonicalUrl,
                        ],
                        'headline' => $post->title,
                        'description' => $description,
                        'image' => [$imageUrl],
                        'datePublished' => $publishedIso,
                        'dateModified' => $modifiedIso,
                        'inLanguage' => 'de-DE',
                        'articleSection' => $post->category?->name ?? $post->class_name ?? 'Schulleben',
                        'author' => [
                            '@type' => 'Organization',
                            'name' => 'Mühlmattenschule Freiburg-Hochdorf',
                            'url' => url('/'),
                        ],
                        'publisher' => [
                            '@id' => url('/').'#school',
                        ],
                    ],
                ],
            ]),
        ]);
    }
}
