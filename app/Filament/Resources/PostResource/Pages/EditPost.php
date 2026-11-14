<?php

declare(strict_types=1);

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditPost extends EditRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = PostResource::class;

    /**
     * Seitentitel mit der Überschrift des Beitrags.
     */
    public function getTitle(): string
    {
        /** @var Post $post */
        $post = $this->getRecord();

        return $post->title;
    }

    /**
     * Status, Klasse und letzte Änderung dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        /** @var Post $post */
        $post = $this->getRecord();

        return collect([
            $post->status?->label(),
            $post->class_name,
            $post->updated_at ? 'Aktualisiert am '.$post->updated_at->format('d.m.Y H:i') : null,
        ])->filter()->implode(' · ');
    }

    /**
     * Kopfzeilen-Aktion: „Auf Website ansehen“.
     *
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        /** @var Post $post */
        $post = $this->getRecord();

        return [
            Action::make('preview')
                ->label('Auf Website ansehen')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('gray')
                ->url('/aktuelles/'.$post->slug, shouldOpenInNewTab: true),
        ];
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
