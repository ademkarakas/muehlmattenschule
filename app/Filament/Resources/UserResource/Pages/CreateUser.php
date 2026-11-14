<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = UserResource::class;

    /**
     * Seitentitel des Formulars.
     */
    public function getTitle(): string
    {
        return 'Neues Benutzerkonto';
    }

    /**
     * Kurze Erklärung unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        return 'Legen Sie ein Konto für Schulleitung, Sekretariat oder eine Lehrkraft an.';
    }
}
