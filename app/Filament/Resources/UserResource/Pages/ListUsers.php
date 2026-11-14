<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Concerns\HasSupportCallListHeader;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    use HasSupportCallListHeader;

    protected static string $resource = UserResource::class;

    /**
     * Zeigt die Gesamtzahl der Benutzerkonten dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        $count = User::query()->count();

        return "Insgesamt {$count} ".($count === 1 ? 'Benutzerkonto' : 'Benutzerkonten');
    }
}
