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

        $tones = ['avatar-tone-1', 'avatar-tone-2', 'avatar-tone-3', 'avatar-tone-4'];

        $posts = Post::query()
            ->with('author:id,name')
            ->select(['id', 'title', 'author_id', 'class_name', 'status', 'published_at', 'updated_at'])
            ->latest('published_at')
            ->limit(6)
            ->get()
            ->map(function (Post $post, int $index) use ($user, $tones): array {
                $authorName = $post->author?->name ?? 'Redaktion';
                $cleanAuthor = trim((string) preg_replace('/\s*\([^)]*\)\s*/u', ' ', $authorName));
                $initials = str($cleanAuthor !== '' ? $cleanAuthor : 'MM')
                    ->explode(' ')
                    ->filter(fn (string $part): bool => filled($part))
                    ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
                    ->take(2)
                    ->join('') ?: 'MM';

                return [
                    'title' => $post->title,
                    'date' => ($post->published_at ?? $post->updated_at)?->format('d.m.Y, H:i'),
                    'author' => $cleanAuthor ?: 'Redaktion',
                    'initials' => $initials,
                    'avatarTone' => $tones[$index % count($tones)],
                    'class' => $post->class_name ?: 'Gesamte Schule',
                    'published' => $post->status === PublishStatus::Published,
                    'url' => $user?->can('update', $post) ? PostResource::getUrl('edit', ['record' => $post]) : null,
                ];
            })
            ->all();

        return [
            'posts' => $posts,
            'postsUrl' => PostResource::getUrl('index'),
        ];
    }
}
