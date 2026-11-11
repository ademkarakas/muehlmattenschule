<?php

declare(strict_types=1);

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\PostResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = PostResource::class;

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