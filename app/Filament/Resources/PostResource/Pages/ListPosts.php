<?php

declare(strict_types=1);

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    /**
     * Zeigt die Gesamtzahl der Beiträge dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        $count = Post::query()->count();

        return "Insgesamt {$count} ".($count === 1 ? 'Beitrag' : 'Beiträge');
    }

    /**
     * Kopfzeilen-Aktionen.
     *
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
