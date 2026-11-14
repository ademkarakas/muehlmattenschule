<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlbumResource\Pages;

use App\Filament\Concerns\HasSupportCallListHeader;
use App\Filament\Resources\AlbumResource;
use App\Models\Album;
use Filament\Resources\Pages\ListRecords;

class ListAlbums extends ListRecords
{
    use HasSupportCallListHeader;

    protected static string $resource = AlbumResource::class;

    /**
     * Zeigt die Gesamtzahl der Fotoalben dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        $count = Album::query()->count();

        return "Insgesamt {$count} ".($count === 1 ? 'Fotoalbum' : 'Fotoalben');
    }
}
