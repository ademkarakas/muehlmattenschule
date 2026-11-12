<?php

declare(strict_types=1);

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditCategory extends EditRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = CategoryResource::class;

    protected Width|string|null $maxContentWidth = Width::FourExtraLarge;

    /**
     * Seitentitel mit dem Namen der Kategorie.
     */
    public function getTitle(): string
    {
        /** @var Category $category */
        $category = $this->getRecord();

        return $category->name;
    }

    /**
     * Kurz-URL und Beitragsanzahl dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        /** @var Category $category */
        $category = $this->getRecord();
        $postsCount = $category->posts()->count();

        return "/{$category->slug} · {$postsCount} ".($postsCount === 1 ? 'Beitrag' : 'Beiträge');
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
