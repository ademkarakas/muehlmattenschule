<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Models\Page;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    /**
     * Zeigt die Gesamtzahl der redaktionellen Seiten dezent unter dem Seitentitel.
     */
    public function getSubheading(): ?string
    {
        $count = Page::query()->count();

        return "Insgesamt {$count} " . ($count === 1 ? 'redaktionelle Schulseite' : 'redaktionelle Schulseiten');
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
