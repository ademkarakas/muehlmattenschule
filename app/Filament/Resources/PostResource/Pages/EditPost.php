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
