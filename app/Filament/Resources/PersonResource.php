<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\PersonGroup;
use App\Filament\Resources\PersonResource\Pages\ManagePeople;
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
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string | UnitEnum | null $navigationGroup = 'Schulstruktur & Seiten';

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
                TextInput::make('name')
                    ->label('Vor- und Nachname')
                    ->required()
                    ->maxLength(160),
                TextInput::make('role_title')
                    ->label('Funktion / Aufgabe')
                    ->placeholder('z. B. Rektorin, Klassenlehrerin, Sekretariat')
                    ->required()
                    ->maxLength(160),
                Select::make('group')
                    ->label('Bereich')
                    ->options(PersonGroup::options())
                    ->default(PersonGroup::Kollegium->value)
                    ->required(),
                TextInput::make('class_name')
                    ->label('Klasse (optional)')
                    ->placeholder('z. B. Klasse 2a')
                    ->maxLength(60),
                TextInput::make('email')
                    ->label('E-Mail-Adresse (optional)')
                    ->email()
                    ->maxLength(160),
                TextInput::make('sort_order')
                    ->label('Reihenfolge')
                    ->numeric()
                    ->default(0),
                SpatieMediaLibraryFileUpload::make('portrait')
                    ->label('Porträtfoto (optional, nur mit Einwilligung)')
                    ->collection('portrait')
                    ->image()
                    ->maxSize(6144)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Auf der Website anzeigen')
                    ->default(true),
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
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role_title')
                    ->label('Funktion')
                    ->searchable(),
                TextColumn::make('class_name')
                    ->label('Klasse')
                    ->placeholder('—'),
                TextColumn::make('group')
                    ->label('Bereich')
                    ->badge()
                    ->formatStateUsing(fn (PersonGroup $state): string => $state->label()),
                IconColumn::make('is_active')
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
            'index' => ManagePeople::route('/'),
        ];
    }
}

