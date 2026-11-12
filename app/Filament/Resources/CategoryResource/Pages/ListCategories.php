<?php

declare(strict_types=1);

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    /**
     * Zeigt die Gesamtzahl der Beitragskategorien dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        $count = Category::query()->count();

        return "Insgesamt {$count} ".($count === 1 ? 'Kategorie' : 'Kategorien');
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
