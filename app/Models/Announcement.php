<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AnnouncementLevel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'text',
    'level',
    'link_url',
    'link_label',
    'visible_from',
    'visible_until',
    'is_active',
])]
class Announcement extends Model
{
    use HasFactory;

    /**
     * Attribute, die automatisch gecastet werden.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level' => AnnouncementLevel::class,
            'visible_from' => 'datetime',
            'visible_until' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Filtert auf aktuell sichtbare Ankündigungen.
     *
     * @param  Builder<Announcement>  $query
     * @return Builder<Announcement>
     */
    public function scopeCurrentlyVisible(Builder $query): Builder
    {
        $now = now();

        return $query
            ->where('is_active', true)
            ->where(function (Builder $inner) use ($now): void {
                $inner->whereNull('visible_from')
                    ->orWhere('visible_from', '<=', $now);
            })
            ->where(function (Builder $inner) use ($now): void {
                $inner->whereNull('visible_until')
                    ->orWhere('visible_until', '>=', $now);
            });
    }
}
