<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\PersonGroup;
use App\Filament\Resources\PersonResource\Pages\CreatePerson;
use App\Filament\Resources\PersonResource\Pages\EditPerson;
use App\Filament\Resources\PersonResource\Pages\ListPeople;
use App\Models\Person;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Schulstruktur & Seiten';

    protected static ?string $navigationLabel = 'Kollegium & Team';

    protected static ?string $modelLabel = 'Person';

    protected static ?string $pluralModelLabel = 'Personen';

    protected static ?int $navigationSort = 2;

    /**
     * Definiert das Formular für eine Person des Schulteams.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personendaten & Zuordnung')
                    ->icon(Heroicon::OutlinedUserGroup)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Vor- und Nachname')
                            ->prefixIcon(Heroicon::OutlinedUser)
                            ->required()
                            ->maxLength(160),
                        TextInput::make('role_title')
                            ->label('Funktion / Aufgabe')
                            ->placeholder('z. B. Rektorin, Klassenlehrerin, Sekretariat')
                            ->prefixIcon(Heroicon::OutlinedBriefcase)
                            ->required()
                            ->maxLength(160),
                        Select::make('group')
                            ->label('Bereich')
                            ->prefixIcon(Heroicon::OutlinedUserGroup)
                            ->options(PersonGroup::options())
                            ->native(false)
                            ->default(PersonGroup::Kollegium->value)
                            ->required(),
                        TextInput::make('class_name')
                            ->label('Klasse (optional)')
                            ->placeholder('z. B. Klasse 2a')
                            ->prefixIcon(Heroicon::OutlinedAcademicCap)
                            ->maxLength(60),
                        TextInput::make('email')
                            ->label('E-Mail-Adresse (optional)')
                            ->prefixIcon(Heroicon::OutlinedEnvelope)
                            ->email()
                            ->maxLength(160),
                        TextInput::make('sort_order')
                            ->label('Reihenfolge')
                            ->prefixIcon(Heroicon::OutlinedArrowsUpDown)
                            ->numeric()
                            ->default(0),
                        SpatieMediaLibraryFileUpload::make('portrait')
                            ->label('Porträtfoto (optional, nur mit Einwilligung)')
                            ->collection('portrait')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(6144)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Auf der Website anzeigen')
                            ->default(true)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * Definiert die Tabellenansicht des Kollegiums.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                SpatieMediaLibraryImageColumn::make('portrait')
                    ->label('')
                    ->collection('portrait')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn (Person $record): string => collect([$record->role_title, $record->class_name])->filter()->implode(' · ')),
                TextColumn::make('group')
                    ->label('Bereich')
                    ->badge()
                    ->formatStateUsing(fn (PersonGroup $state): string => $state->label()),
                IconColumn::make('is_active')
                    ->label('Sichtbar')
                    ->boolean(),
            ])
            ->recordUrl(fn (Person $record): ?string => auth()->user()?->can('update', $record)
                ? static::getUrl('edit', ['record' => $record])
                : null)
            ->searchPlaceholder('Name oder Funktion suchen...')
            ->emptyStateIcon(Heroicon::OutlinedUserGroup)
            ->emptyStateHeading('Keine Personen gefunden')
            ->emptyStateDescription('Legen Sie ein neues Teammitglied des Kollegiums oder der Schulleitung an.')
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
            'index' => ListPeople::route('/'),
            'create' => CreatePerson::route('/create'),
            'edit' => EditPerson::route('/{record}/edit'),
        ];
    }
}
