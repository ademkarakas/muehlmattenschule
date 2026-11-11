<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages\CreateEvent;
use App\Filament\Resources\EventResource\Pages\EditEvent;
use App\Filament\Resources\EventResource\Pages\ListEvents;
use App\Models\Event;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Aktuelles & Schulleben';

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
                    ->label('Auf der Startseite („Nächste Termine“) & im Kalender anzeigen')
                    ->helperText('Kommende Termine ab dem heutigen Datum werden automatisch rechts auf der Startseite unter „Nächste Termine“ sowie im Schulkalender angezeigt.')
                    ->default(true),
                TextInput::make('location')
                    ->label('Ort (optional)')
                    ->placeholder('z. B. Schulhof, Musikraum')
                    ->maxLength(180),
                TextInput::make('class_name')
                    ->label('Klasse / Zielgruppe (optional)')
                    ->placeholder('z. B. Alle Klassen, Klasse 1 & 2, Elternbeirat')
                    ->maxLength(50),
                Select::make('category')
                    ->label('Termin-Art')
                    ->options([
                        'Termin' => 'Termin',
                        'Schulfest' => 'Schulfest & Feier',
                        'Sportfest' => 'Sport & Wettbewerb',
                        'Projekttag' => 'Projekttag',
                        'Exkursion' => 'Ausflug / Exkursion',
                        'Gremien' => 'Elternbeirat / Gremien',
                        'Ferien' => 'Ferien / schulfrei',
                    ])
                    ->default('Termin')
                    ->required(),
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
                TextColumn::make('title')
                    ->label('Termin')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->wrap()
                    ->description(fn (Event $record): string => collect([
                        $record->starts_at?->format($record->all_day ? 'd.m.Y' : 'd.m.Y H:i'),
                        $record->class_name,
                        $record->category,
                        $record->location,
                    ])->filter()->implode(' · ')),
                ToggleColumn::make('is_published')
                    ->label('Sichtbar'),
                TextColumn::make('starts_at')
                    ->label('Beginn')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(fn (Event $record): ?string => auth()->user()?->can('update', $record)
                ? static::getUrl('edit', ['record' => $record])
                : null)
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
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
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
