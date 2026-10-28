<?php

declare(strict_types=1);

namespace App\Filament\Resources\PersonResource\Pages;

use App\Filament\Resources\PersonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePeople extends ManageRecords
{
    protected static string $resource = PersonResource::class;

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

