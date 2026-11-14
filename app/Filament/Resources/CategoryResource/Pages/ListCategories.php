<?php

declare(strict_types=1);

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Concerns\HasSupportCallListHeader;
use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    use HasSupportCallListHeader;

    protected static string $resource = CategoryResource::class;

    /**
     * Zeigt die Gesamtzahl der Beitragskategorien dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        $count = Category::query()->count();

        return "Insgesamt {$count} ".($count === 1 ? 'Kategorie' : 'Kategorien');
    }
}
