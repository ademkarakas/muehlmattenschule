<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\PublishStatus;
use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Widgets\Widget;

class ActivityOverviewWidget extends Widget
{
    protected static ?int $sort = 2;

    /**
     * Widget wird direkt mit der Seite gerendert (kein zusätzlicher Lazy-Load-Request).
     */
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.activity-overview';

    /**
     * Liefert die kompakte Liste der neuesten Beiträge.
     *
     * @return array{posts: list<array<string, mixed>>, postsUrl: string}
     */
    protected function getViewData(): array
    {
        $user = auth()->user();

        $posts = Post::query()
            ->select(['id', 'title', 'class_name', 'status', 'published_at'])
            ->latest('published_at')
            ->limit(5)
            ->get()
            ->map(fn (Post $post): array => [
                'title' => $post->title,
                'date' => $post->published_at?->format('d.m.Y'),
                'class' => $post->class_name ?: 'Schule',
                'published' => $post->status === PublishStatus::Published,
                'url' => $user?->can('update', $post) ? PostResource::getUrl('edit', ['record' => $post]) : null,
            ])
            ->all();

        return [
            'posts' => $posts,
            'postsUrl' => PostResource::getUrl('index'),
        ];
    }
}
