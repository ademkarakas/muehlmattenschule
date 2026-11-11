<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\PageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = PageResource::class;
}
