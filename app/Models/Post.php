<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    'album_id',
    'author_id',
    'is_featured',
    'status',
    'published_at',
])]
class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * Attribute, die automatisch gecastet werden.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'status' => PublishStatus::class,
            'published_at' => 'datetime',
        ];
    }

    /**
     * Zugehörige Kategorie des Beitrags.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
            ->useDisk('public');
    }

    /**
     * Registriert WebP-Bildkonvertierungen für das Titelbild.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('card')
            ->fit(Fit::Crop, 640, 400)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('hero')
            ->fit(Fit::Max, 1600, 900)
            ->format('webp')
            ->nonQueued();
    }
}
