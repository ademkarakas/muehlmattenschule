<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\PublishStatus;
use App\Filament\Resources\AlbumResource\Pages\CreateAlbum;
use App\Filament\Resources\AlbumResource\Pages\EditAlbum;
use App\Filament\Resources\AlbumResource\Pages\ListAlbums;
use App\Models\Album;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class AlbumResource extends Resource
{
    protected static ?string $model = Album::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|UnitEnum|null $navigationGroup = 'Aktuelles & Schulleben';

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
                Section::make('Erstellt von')
                    ->icon(Heroicon::OutlinedInformationCircle)
                    ->schema([
                        TextEntry::make('author_info')
                            ->hiddenLabel()
                            ->state(fn (?Album $record): string => 'Erstellt von '.($record?->author?->name ?? 'Unbekannt')
                                .' am '.$record?->created_at?->format('d.m.Y \u\m H:i')
                                .' Uhr · Zuletzt geändert am '.$record?->updated_at?->format('d.m.Y \u\m H:i').' Uhr'),
                    ])
                    ->visibleOn('edit'),
                Section::make('Album-Angaben')
                    ->icon(Heroicon::OutlinedPhoto)
                    ->schema([
                        TextInput::make('title')
                            ->label('Titel des Fotoalbums')
                            ->prefixIcon(Heroicon::OutlinedPhoto)
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('slug', Str::slug((string) $state))),
                        TextInput::make('slug')
                            ->label('Kurz-URL (Slug)')
                            ->prefixIcon(Heroicon::OutlinedLink)
                            ->required()
                            ->maxLength(220)
                            ->unique(ignoreRecord: true),
                        DatePicker::make('event_date')
                            ->label('Datum der Aktivität')
                            ->prefixIcon(Heroicon::OutlinedCalendarDays)
                            ->default(now()),
                        TextInput::make('class_name')
                            ->label('Klasse (optional, z. B. Klasse 2a)')
                            ->prefixIcon(Heroicon::OutlinedAcademicCap)
                            ->default(fn (): ?string => auth()->user()?->class_name)
                            ->maxLength(60),
                        Textarea::make('description')
                            ->label('Kurzbeschreibung')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Fotos hochladen & DSGVO-Freigabe')
                    ->icon(Heroicon::OutlinedShieldCheck)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('photos')
                            ->label('Fotos (Mehrfachauswahl & Sortierung per Ziehen möglich)')
                            ->collection('photos')
                            ->multiple()
                            ->reorderable()
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(10240)
                            ->maxFiles(120)
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
            ->modifyQueryUsing(fn ($query) => $query->withCount('media')->with('author'))
            ->columns([
                SpatieMediaLibraryImageColumn::make('photos')
                    ->label('Vorschau')
                    ->collection('photos')
                    ->conversion('thumb')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->imageSize(38),
                TextColumn::make('title')
                    ->label('Album')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->wrap()
                    ->lineClamp(2)
                    ->description(fn (Album $record): string => collect([
                        $record->author?->name ?? 'Unbekannt',
                        $record->event_date?->format('d.m.Y'),
                        $record->class_name ?: 'Alle Klassen',
                        $record->totalPhotoCount().' Fotos',
                    ])->filter()->implode(' · ')),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (PublishStatus $state): string => $state->label())
                    ->color(fn (PublishStatus $state): string => $state === PublishStatus::Published ? 'success' : 'gray'),
                IconColumn::make('consent_confirmed')
                    ->label('Einwilligung')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('author.name')
                    ->label('Erstellt von')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(fn (Album $record): ?string => auth()->user()?->can('update', $record)
                ? static::getUrl('edit', ['record' => $record])
                : null)
            ->searchPlaceholder('Albumtitel suchen...')
            ->emptyStateIcon(Heroicon::OutlinedPhoto)
            ->emptyStateHeading('Keine Fotoalben gefunden')
            ->emptyStateDescription('Legen Sie ein neues Fotoalbum mit DSGVO-Freigabe an.')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(PublishStatus::options()),
            ])
            ->recordActions([
                Action::make('preview')
                    ->label('Auf Website ansehen')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->color('gray')
                    ->url(fn (Album $record): string => '/galerie/'.$record->slug, shouldOpenInNewTab: true)
                    ->iconButton(),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
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
            'index' => ListAlbums::route('/'),
            'create' => CreateAlbum::route('/create'),
            'edit' => EditAlbum::route('/{record}/edit'),
        ];
    }
}
