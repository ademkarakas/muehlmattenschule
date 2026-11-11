<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\PageResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = PageResource::class;

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