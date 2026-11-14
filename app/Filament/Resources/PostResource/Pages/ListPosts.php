<?php

declare(strict_types=1);

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Concerns\HasSupportCallListHeader;
use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Resources\Pages\ListRecords;

class ListPosts extends ListRecords
{
    use HasSupportCallListHeader;

    protected static string $resource = PostResource::class;

    /**
     * Zeigt die Gesamtzahl der Beiträge dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        $count = Post::query()->count();

        return "Insgesamt {$count} ".($count === 1 ? 'Beitrag' : 'Beiträge');
    }
}
