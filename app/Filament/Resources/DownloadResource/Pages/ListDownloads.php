<?php

declare(strict_types=1);

namespace App\Filament\Resources\DownloadResource\Pages;

use App\Filament\Resources\DownloadResource;
use App\Models\Download;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDownloads extends ListRecords
{
    protected static string $resource = DownloadResource::class;

    /**
     * Zeigt die Dokumentenanzahl dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        $published = Download::query()->where('is_published', true)->count();
        $total = Download::query()->count();

        return "{$published} veröffentlichte Dokumente & Formulare ({$total} insgesamt)";
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
