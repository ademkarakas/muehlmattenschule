<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'starts_at',
    'ends_at',
    'all_day',
    'location',
    'description',
    'is_published',
])]
class Event extends Model
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
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'all_day' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Filtert auf veröffentlichte zukünftige (oder heute stattfindende) Termine.
     *
     * @param  Builder<Event>  $query
     * @return Builder<Event>
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where(function (Builder $inner): void {
                $inner->where('starts_at', '>=', now()->startOfDay())
                    ->orWhere('ends_at', '>=', now()->startOfDay());
            })
            ->orderBy('starts_at');
    }
}
