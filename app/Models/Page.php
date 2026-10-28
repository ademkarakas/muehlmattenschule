<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'slug',
    'section',
    'menu_order',
    'summary',
    'content',
    'status',
])]
class Page extends Model
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
            'menu_order' => 'integer',
            'content' => 'array',
            'status' => PublishStatus::class,
        ];
    }

    /**
     * Filtert auf veröffentlichte Seiten.
     *
     * @param  Builder<Page>  $query
     * @return Builder<Page>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PublishStatus::Published);
    }
}
