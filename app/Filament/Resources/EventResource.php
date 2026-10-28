<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages\ManageEvents;
use App\Models\Event;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string | UnitEnum | null $navigationGroup = 'Aktuelles & Schulleben';

    protected static ?string $navigationLabel = 'Termine';

    protected static ?string $modelLabel = 'Termin';

    protected static ?string $pluralModelLabel = 'Termine';

    protected static ?int $navigationSort = 2;

    /**
     * Definiert das Formular für einen Schultermin.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Veranstaltung / Termin')
                    ->required()
                    ->maxLength(200)
                    ->columnSpanFull(),
                DateTimePicker::make('starts_at')
                    ->label('Beginn')
                    ->seconds(false)
                    ->required(),
                DateTimePicker::make('ends_at')
                    ->label('Ende (optional)')
                    ->seconds(false),
                Toggle::make('all_day')
                    ->label('Ganztägiger Termin')
                    ->default(false),
                Toggle::make('is_published')
                    ->label('Auf der Website sichtbar')
                    ->default(true),
                TextInput::make('location')
                    ->label('Ort (optional)')
                    ->placeholder('z. B. Schulhof, Musikraum')
                    ->maxLength(180)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Kurzbeschreibung (optional)')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Definiert die Tabellenansicht der Termine.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('starts_at', 'asc')
            ->columns([
                TextColumn::make('starts_at')
                    ->label('Beginn')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Termin')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->label('Ort')
                    ->placeholder('—'),
                IconColumn::make('all_day')
                    ->label('Ganztägig')
                    ->boolean(),
                IconColumn::make('is_published')
                    ->label('Sichtbar')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Seiten dieser Ressource.
     *
     * @return array<string, \Filament\Resources\Pages\PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => ManageEvents::route('/'),
        ];
    }
}
