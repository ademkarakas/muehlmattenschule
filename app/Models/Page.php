<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublishStatus;
use App\Support\HtmlSanitizer;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'slug',
    'section',
    'menu_group',
    'menu_order',
    'summary',
    'content',
    'status',
])]
class Page extends Model
{
    use HasFactory;

    /**
     * Bereinigt alle Rich-Text- und Bild-URL-Blöcke vor dem Speichern gegen Stored-XSS.
     */
    protected static function booted(): void
    {
        static::saving(function (self $page): void {
            if (! is_array($page->content)) {
                return;
            }

            $sanitizedBlocks = [];
            foreach ($page->content as $block) {
                if (! is_array($block)) {
                    continue;
                }

                $type = $block['type'] ?? null;
                $data = is_array($block['data'] ?? null) ? $block['data'] : [];

                if ($type === 'rich_text' && isset($data['body']) && is_string($data['body'])) {
                    $data['body'] = HtmlSanitizer::clean($data['body']);
                }

                if ($type === 'accordion' && isset($data['items']) && is_array($data['items'])) {
                    $cleanItems = [];
                    foreach ($data['items'] as $item) {
                        if (! is_array($item)) {
                            continue;
                        }
                        if (isset($item['content']) && is_string($item['content'])) {
                            $item['content'] = HtmlSanitizer::clean($item['content']);
                        }
                        $cleanItems[] = $item;
                    }
                    $data['items'] = $cleanItems;
                }

                foreach (['image_url', 'image_1_url', 'image_2_url', 'image_3_url', 'source_url'] as $urlField) {
                    if (array_key_exists($urlField, $data)) {
                        $data[$urlField] = HtmlSanitizer::sanitizeUrl(
                            is_string($data[$urlField]) ? $data[$urlField] : null
                        ) ?? '';
                    }
                }

                $block['data'] = $data;
                $sanitizedBlocks[] = $block;
            }

            $page->content = $sanitizedBlocks;
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
