<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PersonGroup;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[Fillable([
    'name',
    'role_title',
    'class_name',
    'email',
    'group',
    'sort_order',
    'is_active',
])]
class Person extends Model implements HasMedia
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
            'group' => PersonGroup::class,
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Registriert die Mediensammlung für das Porträtfoto.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('portrait')
            ->singleFile()
            ->useDisk('public');
    }

    /**
     * Registriert die quadratische WebP-Vorschau des Porträtfotos.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('avatar')
            ->fit(Fit::Crop, 320, 320)
            ->format('webp')
            ->nonQueued();
    }
}
