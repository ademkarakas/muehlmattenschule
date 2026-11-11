<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[Fillable([
    'title',
    'slug',
    'event_date',
    'class_name',
    'author_id',
    'description',
    'gallery_images',
    'consent_confirmed',
    'status',
    'published_at',
])]
class Album extends Model implements HasMedia
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
            'event_date' => 'date',
            'gallery_images' => 'array',
            'consent_confirmed' => 'boolean',
            'status' => PublishStatus::class,
            'published_at' => 'datetime',
        ];
    }

    /**
     * Verfasser (z. B. Lehrkraft oder Sekretariat), der das Album erstellt hat.
     *
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Beiträge, die mit diesem Fotoalbum verknüpft sind.
     *
     * @return HasMany<Post, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Prüft, ob dieses Fotoalbum öffentlich sichtbar ist (veröffentlicht, freigegeben und DSGVO-Einwilligung bestätigt).
     */
    public function isPubliclyVisible(): bool
    {
        if ($this->status !== PublishStatus::Published || ! $this->consent_confirmed) {
            return false;
        }

        return $this->published_at === null || $this->published_at->lte(now());
    }

    /**
     * Filtert auf öffentlich sichtbare Alben (veröffentlicht + DSGVO-Einwilligung bestätigt).
     *
     * @param  Builder<Album>  $query
     * @return Builder<Album>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', PublishStatus::Published)
            ->where('consent_confirmed', true)
            ->where(function (Builder $inner): void {
                $inner->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Registriert die Mediensammlung für Fotos des Albums.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->useDisk('public');
    }

    /**
     * Registriert WebP-Bildkonvertierungen für schnelle Ladezeiten.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 480, 320)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('large')
            ->fit(Fit::Max, 1600, 1600)
            ->format('webp')
            ->nonQueued();
    }

    /**
     * Liefert alle Fotos des Albums (Spatie Media Library + statische Galerie-Pfade) dedupliziert zurück.
     *
     * @return array<int, array{id: int, thumbUrl: string, largeUrl: string, alt: string}>
     */
    public function resolvedPhotos(): array
    {
        $photos = [];
        $seenUrls = [];

        foreach ($this->getMedia('photos') as $media) {
            $largeUrl = $media->hasGeneratedConversion('large')
                ? $media->getUrl('large')
                : $media->getUrl();
            $thumbUrl = $media->hasGeneratedConversion('thumb')
                ? $media->getUrl('thumb')
                : $media->getUrl();

            $photos[] = [
                'id' => (int) $media->id,
                'thumbUrl' => $thumbUrl,
                'largeUrl' => $largeUrl,
                'alt' => (string) $media->getCustomProperty('alt', $this->title),
            ];
            $seenUrls[basename($media->file_name)] = true;
        }

        if (is_array($this->gallery_images)) {
            $nextId = 10000 + ($this->id ?? 0) * 1000;
            foreach ($this->gallery_images as $index => $entry) {
                $url = is_array($entry) ? (string) ($entry['url'] ?? '') : (string) $entry;
                if ($url === '') {
                    continue;
                }
                $base = basename($url);
                if (isset($seenUrls[$base])) {
                    continue;
                }
                $seenUrls[$base] = true;
                $alt = is_array($entry) && isset($entry['alt']) && is_string($entry['alt'])
                    ? $entry['alt']
                    : sprintf('%s – Foto %d', $this->title, $index + 1);

                $photos[] = [
                    'id' => $nextId + (int) $index,
                    'thumbUrl' => $url,
                    'largeUrl' => $url,
                    'alt' => $alt,
                ];
            }
        }

        return $photos;
    }

    /**
     * Liefert die Gesamtanzahl aller Fotos im Album.
     */
    public function totalPhotoCount(): int
    {
        return count($this->resolvedPhotos());
    }

    /**
     * Liefert das Vorschaubild des Albums (erstes Foto aus Media Library oder gallery_images).
     */
    public function coverThumbUrl(): ?string
    {
        $firstMedia = $this->getFirstMedia('photos');
        if ($firstMedia !== null) {
            return $firstMedia->hasGeneratedConversion('thumb')
                ? $firstMedia->getUrl('thumb')
                : $firstMedia->getUrl();
        }

        if (is_array($this->gallery_images) && $this->gallery_images !== []) {
            $first = $this->gallery_images[0];

            return is_array($first) ? (string) ($first['url'] ?? '') : (string) $first;
        }

        return null;
    }
}
