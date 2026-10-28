<?php

declare(strict_types=1);

namespace App\Filament\Resources\DownloadResource\Pages;

use App\Filament\Resources\DownloadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDownloads extends ManageRecords
{
    protected static string $resource = DownloadResource::class;

    /**
     * Kopfzeilen-Aktionen.
     *
     * @return array<\Filament\Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

