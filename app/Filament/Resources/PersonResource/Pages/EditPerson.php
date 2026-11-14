<?php

declare(strict_types=1);

namespace App\Filament\Resources\PersonResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\PersonResource;
use App\Models\Person;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPerson extends EditRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = PersonResource::class;

    /**
     * Seitentitel mit dem Namen der Person.
     */
    public function getTitle(): string
    {
        /** @var Person $person */
        $person = $this->getRecord();

        return $person->name;
    }

    /**
     * Funktion, Bereich und Klasse dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        /** @var Person $person */
        $person = $this->getRecord();

        return collect([
            $person->role_title,
            $person->group?->label(),
            $person->class_name,
        ])->filter()->implode(' · ');
    }

    /**
     * Speichern, Abbrechen und Löschen nebeneinander in einer zentrierten Leiste.
     *
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
            DeleteAction::make(),
        ];
    }
}
