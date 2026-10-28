<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UpcomingEventsWidget extends TableWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    /**
     * Zeigt die nächsten anstehenden Schultermine auf dem Dashboard.
     */
    public function table(Table $table): Table
    {
        return $table
            ->heading('Nächste Termine')
            ->query(Event::query()->upcoming()->limit(5))
            ->columns([
                TextColumn::make('starts_at')
                    ->label('Datum & Uhrzeit')
                    ->dateTime('d.m.Y H:i'),
                TextColumn::make('title')
                    ->label('Veranstaltung'),
                TextColumn::make('location')
                    ->label('Ort')
                    ->placeholder('—'),
            ])
            ->paginated(false);
    }
}
