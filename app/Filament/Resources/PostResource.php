<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\PublishStatus;
use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Models\Post;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|UnitEnum|null $navigationGroup = 'Aktuelles & Schulleben';

    protected static ?string $navigationLabel = 'Beiträge & Aktivitäten';

    protected static ?string $modelLabel = 'Beitrag';

    protected static ?string $pluralModelLabel = 'Beiträge';

    protected static ?int $navigationSort = 1;

    /**
     * Definiert das Formular zum Erstellen und Bearbeiten von Schul- und Klassenbeiträgen.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(['default' => 1, 'lg' => 3])
                    ->schema([
                        // Hauptspalte (2/3): Inhalt des Beitrags
                        Group::make()
                            ->schema([
                                Section::make('Beitragsinhalt')
                                    ->icon(Heroicon::OutlinedNewspaper)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Überschrift')
                                            ->placeholder('z. B. Ausflug der 4. Klassen zum Mundenhof')
                                            ->prefixIcon(Heroicon::OutlinedNewspaper)
                                            ->required()
                                            ->maxLength(200)
                                            ->extraInputAttributes(['class' => 'text-lg font-semibold'])
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state, string $operation): void {
                                                if ($operation === 'create' || blank($get('slug'))) {
                                                    $set('slug', Str::slug((string) $state));
                                                }
                                            }),
                                        Textarea::make('excerpt')
                                            ->label('Kurzfassung')
                                            ->helperText('Kurzer Vorschautext für Startseite und Beitragsliste (max. 400 Zeichen).')
                                            ->rows(2)
                                            ->maxLength(400),
                                        RichEditor::make('body')
                                            ->label('Bericht / Text')
                                            ->required()
                                            ->minHeight('22rem'),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 2]),

                        // Seitenleiste (1/3): Veröffentlichung, Titelbild, Zuordnung, Erweitert
                        Group::make()
                            ->schema([
                                Section::make('Veröffentlichung')
                                    ->icon(Heroicon::OutlinedRocketLaunch)
                                    ->schema([
                                        Select::make('status')
                                            ->label('Status')
                                            ->options(PublishStatus::options())
                                            ->default(PublishStatus::Published->value)
                                            ->native(false)
                                            ->required(),
                                        DateTimePicker::make('published_at')
                                            ->label('Veröffentlichen am')
                                            ->seconds(false)
                                            ->native(false)
                                            ->default(now()),
                                        Toggle::make('is_featured')
                                            ->label('Auf der Startseite anzeigen')
                                            ->helperText('Alle veröffentlichten Beiträge erscheinen immer unter „Nachrichten & Klassen“.')
                                            ->default(true),
                                        TextEntry::make('author_info')
                                            ->hiddenLabel()
                                            ->state(fn (?Post $record): string => 'Erstellt von '.($record?->author?->name ?? 'Unbekannt')
                                                .' am '.$record?->created_at?->format('d.m.Y \\u\\m H:i')
                                                .' Uhr · Zuletzt geändert am '.$record?->updated_at?->format('d.m.Y \\u\\m H:i').' Uhr')
                                            ->color('gray')
                                            ->size('sm')
                                            ->visibleOn('edit'),
                                    ]),

                                Section::make('Titelbild')
                                    ->icon(Heroicon::OutlinedPhoto)
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('cover')
                                            ->hiddenLabel()
                                            ->collection('cover')
                                            ->image()
                                            ->imageEditor()
                                            ->imageAspectRatio('16:10')
                                            ->automaticallyCropImagesToAspectRatio()
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->maxSize(10240),
                                    ]),

                                Section::make('Zuordnung')
                                    ->icon(Heroicon::OutlinedTag)
                                    ->schema([
                                        TextInput::make('class_name')
                                            ->label('Klasse (optional)')
                                            ->placeholder('z. B. Klasse 2a')
                                            ->default(fn (): ?string => auth()->user()?->class_name)
                                            ->maxLength(60),
                                        Select::make('category_id')
                                            ->label('Kategorie')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->native(false),
                                        Select::make('album_id')
                                            ->label('Verknüpftes Fotoalbum (optional)')
                                            ->relationship('album', 'title')
                                            ->searchable()
                                            ->preload()
                                            ->native(false),
                                    ]),

                                Section::make('Erweitert')
                                    ->icon(Heroicon::OutlinedCog6Tooth)
                                    ->collapsed()
                                    ->schema([
                                        TextInput::make('slug')
                                            ->label('Kurz-URL (Slug)')
                                            ->helperText('Wird automatisch aus der Überschrift erzeugt.')
                                            ->required()
                                            ->maxLength(220)
                                            ->unique(ignoreRecord: true),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Definiert die Tabellenansicht der Beiträge.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->with(['category', 'author']))
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')
                    ->label('Bild')
                    ->collection('cover')
                    ->conversion('card')
                    ->square()
                    ->imageSize(44),
                TextColumn::make('title')
                    ->label('Überschrift')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->wrap()
                    ->lineClamp(2)
                    ->description(fn (Post $record): string => collect([
                        $record->author?->name ?? 'Unbekannt',
                        $record->published_at?->format('d.m.Y H:i'),
                        $record->class_name ?: 'Schule',
                        $record->category?->name,
                    ])->filter()->implode(' · ')),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (PublishStatus $state): string => $state->label())
                    ->color(fn (PublishStatus $state): string => $state === PublishStatus::Published ? 'success' : 'gray'),
                ToggleColumn::make('is_featured')
                    ->label('Startseite')
                    ->disabled(fn (Post $record): bool => ! (auth()->user()?->can('update', $record) ?? false))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('author.name')
                    ->label('Verfasser')
                    ->placeholder('mmsadmin')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->label('Veröffentlicht am')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->recordUrl(fn (Post $record): ?string => auth()->user()?->can('update', $record)
                ? static::getUrl('edit', ['record' => $record])
                : null)
            ->searchPlaceholder('Überschrift oder Verfasser suchen...')
            ->emptyStateIcon(Heroicon::OutlinedNewspaper)
            ->emptyStateHeading('Keine Beiträge gefunden')
            ->emptyStateDescription('Erstellen Sie einen neuen Beitrag oder passen Sie Ihre Such- und Filterkriterien an.')
            ->filters([
                TernaryFilter::make('is_featured')
                    ->label('Auf der Startseite'),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(PublishStatus::options()),
                SelectFilter::make('category')
                    ->label('Kategorie')
                    ->relationship('category', 'name'),
            ])
            ->recordActions([
                Action::make('preview')
                    ->label('Auf Website ansehen')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->color('gray')
                    ->url(fn (Post $record): string => '/aktuelles/'.$record->slug, shouldOpenInNewTab: true)
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
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
