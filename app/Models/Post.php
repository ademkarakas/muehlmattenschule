<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublishStatus;
use App\Support\HtmlSanitizer;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Schema;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[Fillable([
    'title',
    'slug',
    'excerpt',
    'body',
    'category_id',
    'class_name',
    'album_id',
    'gallery_images',
    'author_id',
    'is_featured',
    'status',
    'published_at',
])]
class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * Bereinigt den HTML-Inhalt automatisch vor jedem Speichervorgang gegen XSS.
     */
    protected static function booted(): void
    {
        static::saving(function (self $post): void {
            $post->body = HtmlSanitizer::clean($post->body);
        });

        static::saved(function (self $post): void {
            if ($post->category_id !== null && Schema::hasTable('category_post')) {
                $post->categories()->syncWithoutDetaching([$post->category_id]);
            }
        });
    }

    /**
     * Attribute, die automatisch gecastet werden.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gallery_images' => 'array',
            'is_featured' => 'boolean',
            'status' => PublishStatus::class,
            'published_at' => 'datetime',
        ];
    }

    /**
     * Zugehörige Hauptkategorie des Beitrags.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Alle dem Beitrag zugeordneten Rubriken (Mehrfachzuordnung wie im Original-Archiv).
     *
     * @return BelongsToMany<Category, $this>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }

    /**
     * Optional verknüpftes Fotoalbum.
     *
     * @return BelongsTo<Album, $this>
     */
    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    /**
     * Verfasser des Beitrags.
     *
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Filtert auf öffentlich sichtbare Beiträge.
     *
     * @param  Builder<Post>  $query
     * @return Builder<Post>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', PublishStatus::Published)
            ->where(function (Builder $inner): void {
                $inner->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Registriert die Mediensammlung für das Titelbild.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->useDisk('public');
    }

    /**
     * Registriert WebP-Bildkonvertierungen für das Titelbild.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('card')
            ->fit(Fit::Max, 800, 800)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('hero')
            ->fit(Fit::Max, 1600, 900)
            ->format('webp')
            ->nonQueued();
    }

    /**
     * Liefert die direkt zum Beitrag gehörenden Galerie-Bilder für die Detailansicht.
     *
     * @return array<int, array{id: int, thumbUrl: string, largeUrl: string, alt: string}>
     */
    public function resolvedGalleryPhotos(): array
    {
        if (! is_array($this->gallery_images) || $this->gallery_images === []) {
            return [];
        }

        $photos = [];
        $baseId = 50000 + ($this->id ?? 0) * 1000;
        foreach ($this->gallery_images as $index => $entry) {
            $url = is_array($entry) ? (string) ($entry['url'] ?? '') : (string) $entry;
            if ($url === '') {
                continue;
            }
            $alt = is_array($entry) && isset($entry['alt']) && is_string($entry['alt'])
                ? $entry['alt']
                : sprintf('%s – Bild %d', $this->title, (int) $index + 1);

            $photos[] = [
                'id' => $baseId + (int) $index,
                'thumbUrl' => $url,
                'largeUrl' => $url,
                'alt' => $alt,
            ];
        }

        return $photos;
    }
}
