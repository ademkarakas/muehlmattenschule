<?php

declare(strict_types=1);

namespace App\Filament\Resources\PersonResource\Pages;

use App\Filament\Resources\PersonResource;
use App\Models\Person;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPeople extends ListRecords
{
    protected static string $resource = PersonResource::class;

    /**
     * Zeigt die Personenanzahl des Schulteams dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        $active = Person::query()->where('is_active', true)->count();
        $total = Person::query()->count();

        return "{$active} aktive ".($active === 1 ? 'Person' : 'Personen')." im Schulteam ({$total} insgesamt)";
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
