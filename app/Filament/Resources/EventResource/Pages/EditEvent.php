<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\EventResource;
use App\Models\Event;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = EventResource::class;

    /**
     * Seitentitel mit dem Namen des Termins.
     */
    public function getTitle(): string
    {
        /** @var Event $event */
        $event = $this->getRecord();

        return $event->title;
    }

    /**
     * Datum, Kategorie und Ort dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        /** @var Event $event */
        $event = $this->getRecord();

        return collect([
            $event->starts_at?->format($event->all_day ? 'd.m.Y' : 'd.m.Y H:i'),
            $event->category,
            $event->location,
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
