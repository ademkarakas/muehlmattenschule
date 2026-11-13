<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\Event;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    /**
     * Zeigt die anstehenden und gesamten Termine dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        $upcoming = Event::query()->upcoming()->count();
        $total = Event::query()->count();

        return $upcoming === 1
            ? "1 anstehender Termin ({$total} insgesamt)"
            : "{$upcoming} anstehende Termine ({$total} insgesamt)";
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
