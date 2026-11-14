<?php

declare(strict_types=1);

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\AnnouncementResource;
use App\Models\Announcement;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditAnnouncement extends EditRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = AnnouncementResource::class;

    /**
     * Seitentitel mit dem gekürzten Mitteilungstext.
     */
    public function getTitle(): string
    {
        /** @var Announcement $announcement */
        $announcement = $this->getRecord();

        return Str::limit($announcement->text, 60);
    }

    /**
     * Stufe und Sichtbarkeit dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        /** @var Announcement $announcement */
        $announcement = $this->getRecord();

        return collect([
            $announcement->level?->label(),
            $announcement->is_active ? 'Aktiv auf der Startseite' : 'Inaktiv',
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
