<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    /**
     * Zeigt die Gesamtzahl der Benutzerkonten dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        $count = User::query()->count();

        return "Insgesamt {$count} " . ($count === 1 ? 'Benutzerkonto' : 'Benutzerkonten');
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
