<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\PublishStatus;
use App\Filament\Resources\AlbumResource\Pages\CreateAlbum;
use App\Filament\Resources\AlbumResource\Pages\EditAlbum;
use App\Filament\Resources\AlbumResource\Pages\ListAlbums;
use App\Models\Album;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class AlbumResource extends Resource
{
    protected static ?string $model = Album::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string | UnitEnum | null $navigationGroup = 'Aktuelles & Schulleben';

    protected static ?string $navigationLabel = 'Fotoalben';

    protected static ?string $modelLabel = 'Fotoalbum';

    protected static ?string $pluralModelLabel = 'Fotoalben';

    protected static ?int $navigationSort = 3;

    /**
     * Definiert das Formular für Fotoalben inklusive DSGVO-Pflichtbestätigung.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Album-Angaben')
                    ->schema([
                        TextInput::make('title')
                            ->label('Titel des Fotoalbums')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('slug', Str::slug((string) $state))),
                        TextInput::make('slug')
                            ->label('Kurz-URL (Slug)')
                            ->required()
                            ->maxLength(220)
                            ->unique(ignoreRecord: true),
                        DatePicker::make('event_date')
                            ->label('Datum der Aktivität')
                            ->default(now()),
                        TextInput::make('class_name')
                            ->label('Klasse (optional, z. B. Klasse 2a)')
                            ->default(fn (): ?string => auth()->user()?->class_name)
                            ->maxLength(60),
                        Textarea::make('description')
                            ->label('Kurzbeschreibung')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Fotos hochladen & DSGVO-Freigabe')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('photos')
                            ->label('Fotos (Mehrfachauswahl & Sortierung per Ziehen möglich)')
                            ->collection('photos')
                            ->multiple()
                            ->reorderable()
                            ->image()
                            ->maxSize(10240)
                            ->maxFiles(60)
                            ->columnSpanFull(),
                        Checkbox::make('consent_confirmed')
                            ->label('DSGVO-Bestätigung: Für alle abgebildeten Kinder liegen schriftliche Einwilligungserklärungen der Erziehungsberechtigten vor.')
                            ->accepted()
                            ->validationMessages([
                                'accepted' => 'Ohne bestätigte Einwilligungserklärung der Eltern darf das Fotoalbum nicht gespeichert werden.',
                            ])
                            ->columnSpanFull(),
                        Select::make('status')
                            ->label('Status')
                            ->options(PublishStatus::options())
                            ->default(PublishStatus::Published->value)
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Veröffentlichen am')
                            ->seconds(false)
                            ->default(now()),
                    ])
                    ->columns(2),
            ]);
    }

    /**
     * Definiert die Tabellenansicht der Fotoalben.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('event_date', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label('Album')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('class_name')
                    ->label('Klasse')
                    ->badge()
                    ->placeholder('Alle Klassen'),
                TextColumn::make('event_date')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('media_count')
                    ->label('Fotos')
                    ->counts('media'),
                IconColumn::make('consent_confirmed')
                    ->label('Einwilligung')
                    ->boolean(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (PublishStatus $state): string => $state->label())
                    ->color(fn (PublishStatus $state): string => $state === PublishStatus::Published ? 'success' : 'gray'),
                TextColumn::make('author.name')
                    ->label('Erstellt von')
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(PublishStatus::options()),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => ListAlbums::route('/'),
            'create' => CreateAlbum::route('/create'),
            'edit' => EditAlbum::route('/{record}/edit'),
        ];
    }
}

