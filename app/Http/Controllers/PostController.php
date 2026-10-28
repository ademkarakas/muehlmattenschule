<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
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
            ->with(['category:id,name,slug,color', 'media'])
            ->orderByDesc('published_at');

        if ($categorySlug !== '') {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($classFilter !== '') {
            $query->where('class_name', $classFilter);
        }

        $paginated = $query->paginate(9)->withQueryString();

        $items = $paginated->getCollection()->map(fn (Post $post): array => [
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
        ])->all();

        $categories = Category::query()
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
        if ($post->album !== null && $post->album->consent_confirmed) {
            $linkedAlbum = [
                'title' => $post->album->title,
                'slug' => $post->album->slug,
                'photosCount' => $post->album->getMedia('photos')->count(),
            ];
        }

        return Inertia::render('posts/show', [
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'body' => $post->body,
                'className' => $post->class_name,
                'publishedAt' => $post->published_at?->format('d.m.Y'),
                'authorName' => $post->author?->name,
                'coverUrl' => $post->getFirstMediaUrl('cover', 'hero') ?: $post->getFirstMediaUrl('cover') ?: null,
                'category' => $post->category ? [
                    'name' => $post->category->name,
                    'slug' => $post->category->slug,
                    'color' => $post->category->color,
                ] : null,
                'linkedAlbum' => $linkedAlbum,
            ],
        ]);
    }
}
