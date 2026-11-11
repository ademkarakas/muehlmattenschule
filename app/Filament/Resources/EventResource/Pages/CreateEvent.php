<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Concerns\HasCenteredStickyFormActions;
use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    use HasCenteredStickyFormActions;

    protected static string $resource = EventResource::class;
}

