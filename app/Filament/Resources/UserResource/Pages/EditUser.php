<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = UserResource::class;

    /**
     * Seitentitel mit dem Namen der Person.
     */
    public function getTitle(): string
    {
        /** @var User $user */
        $user = $this->getRecord();

        return $user->name;
    }

    /**
     * Rolle und E-Mail-Adresse dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        /** @var User $user */
        $user = $this->getRecord();

        return collect([$user->role?->label(), $user->email])->filter()->implode(' · ');
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
