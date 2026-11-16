<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\PersonGroup;
use App\Enums\PublishStatus;
use App\Filament\Resources\PageResource\Pages\CreatePage;
use App\Filament\Resources\PageResource\Pages\EditPage;
use App\Filament\Resources\PageResource\Pages\ListPages;
use App\Models\Page;
use App\Support\ImageExifStripper;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use UnitEnum;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Schulstruktur & Seiten';

    protected static ?string $navigationLabel = 'Schulseiten';

    protected static ?string $modelLabel = 'Schulseite';

    protected static ?string $pluralModelLabel = 'Schulseiten';

    protected static ?int $navigationSort = 1;

    /**
     * Definiert das Formular für feste Schulseiten mit flexiblen Inhaltsblöcken.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Seiten-Einstellungen')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->schema([
                        TextInput::make('title')
                            ->label('Seitentitel')
                            ->prefixIcon(Heroicon::OutlinedDocumentText)
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
                        Select::make('section')
                            ->label('Menübereich')
                            ->prefixIcon(Heroicon::OutlinedFolderOpen)
                            ->options([
                                'unsere-schule' => 'Unsere Schule',
                                'konzepte-projekte' => 'Konzepte & Projekte',
                                'angebote' => 'Angebote & Betreuung',
                                'schulleben' => 'Schulleben (Übersicht)',
                            ])
                            ->native(false)
                            ->default('unsere-schule')
                            ->required(),
                        TextInput::make('menu_group')
                            ->label('Untergruppe im Menü (optional)')
                            ->placeholder('z. B. Profil & Menschen, Gremien & Partner')
                            ->prefixIcon(Heroicon::OutlinedTag)
                            ->maxLength(100),
                        TextInput::make('menu_order')
                            ->label('Reihenfolge im Menü')
                            ->prefixIcon(Heroicon::OutlinedArrowsUpDown)
                            ->numeric()
                            ->default(1),
                        Select::make('status')
                            ->label('Status')
                            ->options(PublishStatus::options())
                            ->native(false)
                            ->default(PublishStatus::Published->value)
                            ->required(),
                        Textarea::make('summary')
                            ->label('Kurzbeschreibung (Untertitel)')
                            ->rows(2)
                            ->maxLength(400)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Seiteninhalt (Baukasten)')
                    ->icon(Heroicon::OutlinedSquares2x2)
                    ->schema([
                        Builder::make('content')
                            ->label('Inhaltsblöcke')
                            ->addActionLabel('Abschnitt hinzufügen')
                            ->blocks([
                                Block::make('rich_text')
                                    ->label('Textabschnitt (optional mit Seitenbild)')
                                    ->icon(Heroicon::OutlinedDocumentText)
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Zwischenüberschrift (optional)')
                                            ->placeholder('z. B. Schulgebäude und -gelände')
                                            ->maxLength(160),
                                        RichEditor::make('body')
                                            ->label('Text')
                                            ->required(),
                                        Section::make('Seitenbild neben dem Text (optional – 2-Spalten-Layout)')
                                            ->description('Wenn hier ein Bild hinterlegt wird, erscheint es auf Desktop-Bildschirmen harmonisch links oder rechts neben dem Text statt darunter.')
                                            ->icon(Heroicon::OutlinedPhoto)
                                            ->collapsible()
                                            ->collapsed(fn (Get $get): bool => blank($get('image_url')))
                                            ->schema([
                                                TextInput::make('image_url')
                                                    ->label('Bild-Pfad oder URL')
                                                    ->placeholder('/images/school/unsere-schule-innen.jpg')
                                                    ->datalist(self::availableSchoolImagePaths())
                                                    ->suffixAction(self::makeImageUploadAction('image_url'))
                                                    ->columnSpanFull(),
                                                Select::make('image_position')
                                                    ->label('Bild-Position (Desktop)')
                                                    ->options([
                                                        'right' => 'Rechts neben dem Text (Standard)',
                                                        'left' => 'Links neben dem Text',
                                                    ])
                                                    ->default('right')
                                                    ->native(false),
                                                Select::make('image_fit')
                                                    ->label('Bild-Darstellung')
                                                    ->options([
                                                        'cover' => 'Formatfüllend (ideal für Fotos)',
                                                        'contain' => 'Vollständig sichtbar (ideal für Logos, Karten & Grafiken)',
                                                    ])
                                                    ->default('cover')
                                                    ->native(false),
                                                TextInput::make('image_caption')
                                                    ->label('Bildunterschrift (optional)')
                                                    ->placeholder('Kurze Beschreibung unter dem Bild')
                                                    ->maxLength(240)
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns(2),
                                    ]),
                                Block::make('info_box')
                                    ->label('Infokasten / Hinweis')
                                    ->icon(Heroicon::OutlinedInformationCircle)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Titel')
                                            ->required()
                                            ->maxLength(160),
                                        Textarea::make('text')
                                            ->label('Hinweistext')
                                            ->rows(3)
                                            ->required(),
                                    ]),
                                Block::make('image_banner')
                                    ->label('Großes Seitenbild / Foto')
                                    ->icon(Heroicon::OutlinedPhoto)
                                    ->schema([
                                        TextInput::make('image_url')
                                            ->label('Bild-Pfad oder URL')
                                            ->placeholder('/images/school/gebaeude-wide.jpg')
                                            ->datalist(self::availableSchoolImagePaths())
                                            ->suffixAction(self::makeImageUploadAction('image_url'))
                                            ->required(),
                                        TextInput::make('caption')
                                            ->label('Bildunterschrift (optional)')
                                            ->maxLength(200),
                                    ]),
                                Block::make('image_gallery')
                                    ->label('Fotoreihe (2–3 Bilder)')
                                    ->icon(Heroicon::OutlinedSquares2x2)
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Überschrift (optional)')
                                            ->maxLength(160),
                                        Grid::make(['default' => 1, 'md' => 2])
                                            ->schema([
                                                TextInput::make('image_1_url')
                                                    ->label('Bild 1 URL')
                                                    ->datalist(self::availableSchoolImagePaths())
                                                    ->suffixAction(self::makeImageUploadAction('image_1_url'))
                                                    ->required(),
                                                TextInput::make('image_1_caption')
                                                    ->label('Bild 1 Unterschrift'),
                                                TextInput::make('image_2_url')
                                                    ->label('Bild 2 URL')
                                                    ->datalist(self::availableSchoolImagePaths())
                                                    ->suffixAction(self::makeImageUploadAction('image_2_url')),
                                                TextInput::make('image_2_caption')
                                                    ->label('Bild 2 Unterschrift'),
                                                TextInput::make('image_3_url')
                                                    ->label('Bild 3 URL')
                                                    ->datalist(self::availableSchoolImagePaths())
                                                    ->suffixAction(self::makeImageUploadAction('image_3_url')),
                                                TextInput::make('image_3_caption')
                                                    ->label('Bild 3 Unterschrift'),
                                            ]),
                                        TextInput::make('caption')
                                            ->label('Hinweis / Bildquelle unter der Fotoreihe (optional)')
                                            ->placeholder('z. B. Die Luftbilder hat uns freundlicherweise das Ballonteam Norbert Blau zur Verfügung gestellt.')
                                            ->maxLength(240),
                                        Grid::make(['default' => 1, 'md' => 2])
                                            ->schema([
                                                TextInput::make('source_label')
                                                    ->label('Quellen-Linktext (optional)')
                                                    ->placeholder('z. B. Ballonteam Norbert Blau')
                                                    ->maxLength(120),
                                                TextInput::make('source_url')
                                                    ->label('Quellen-URL (optional)')
                                                    ->placeholder('https://www.freiburg-schwarzwald.net')
                                                    ->maxLength(240),
                                            ]),
                                    ]),
                                Block::make('people_list')
                                    ->label('Personenliste (Kollegium / Leitung)')
                                    ->icon(Heroicon::OutlinedUserGroup)
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Abschnitts-Überschrift (optional)')
                                            ->maxLength(160),
                                        Select::make('group')
                                            ->label('Anzuzeigende Gruppe')
                                            ->options([
                                                'all' => 'Alle Gruppen',
                                                ...PersonGroup::options(),
                                            ])
                                            ->default('all')
                                            ->required(),
                                    ]),
                                Block::make('accordion')
                                    ->label('Aufklappbare Fragen & Antworten (FAQ / Akkordeon)')
                                    ->icon(Heroicon::OutlinedQuestionMarkCircle)
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Abschnitts-Überschrift (optional)')
                                            ->maxLength(160),
                                        Repeater::make('items')
                                            ->label('Einträge')
                                            ->schema([
                                                TextInput::make('question')
                                                    ->label('Frage / Titel')
                                                    ->required()
                                                    ->maxLength(180),
                                                RichEditor::make('content')
                                                    ->label('Antwort / Inhalt')
                                                    ->required(),
                                            ])
                                            ->defaultItems(1),
                                    ]),
                                Block::make('downloads_box')
                                    ->label('Downloads-Kasten (PDF-Dokumente)')
                                    ->icon(Heroicon::OutlinedArrowDownTray)
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Überschrift')
                                            ->default('Passende Elternbriefe & Formulare')
                                            ->maxLength(160),
                                        TextInput::make('category')
                                            ->label('Download-Kategorie filtern (optional, z. B. Förderverein & Formulare)')
                                            ->maxLength(120),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * Definiert die Tabellenansicht der Schulseiten.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('menu_order')
            ->columns([
                TextColumn::make('title')
                    ->label('Seitentitel')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn (Page $record): string => match ($record->section) {
                        'konzepte-projekte' => 'Konzepte & Projekte',
                        'angebote' => 'Angebote & Betreuung',
                        'schulleben' => 'Schulleben',
                        default => 'Unsere Schule',
                    }.($record->menu_group ? ' ('.$record->menu_group.')' : '').' · Position '.$record->menu_order),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (PublishStatus $state): string => $state->label())
                    ->color(fn (PublishStatus $state): string => $state === PublishStatus::Published ? 'success' : 'gray'),
            ])
            ->recordUrl(fn (Page $record): ?string => auth()->user()?->can('update', $record)
                ? static::getUrl('edit', ['record' => $record])
                : null)
            ->searchPlaceholder('Seitentitel suchen...')
            ->emptyStateIcon(Heroicon::OutlinedDocumentText)
            ->emptyStateHeading('Keine Schulseiten gefunden')
            ->emptyStateDescription('Legen Sie eine neue feste Schulseite im Menü an.')
            ->recordActions([
                Action::make('preview')
                    ->label('Auf Website ansehen')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->color('gray')
                    ->url(fn (Page $record): string => '/'.$record->section.'/'.$record->slug, shouldOpenInNewTab: true)
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
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }

    /**
     * Liefert die vorhandenen lokalen Schulbilder als Autocomplete-Vorschläge für Bild-URL-Felder.
     *
     * @return array<int, string>
     */
    private static function availableSchoolImagePaths(): array
    {
        $schoolImageDir = public_path('images/school');
        if (! is_dir($schoolImageDir)) {
            return [];
        }

        $files = glob($schoolImageDir.'/*.{jpg,jpeg,png,webp,svg}', GLOB_BRACE) ?: [];
        $paths = array_map(
            static fn (string $file): string => '/images/school/'.basename($file),
            $files
        );

        sort($paths);

        return $paths;
    }

    /**
     * Erzeugt eine kompakte Upload-Aktion direkt am Bild-Pfad-Feld (inkl. DSGVO-EXIF-Bereinigung).
     */
    private static function makeImageUploadAction(string $targetField): Action
    {
        return Action::make('upload_'.$targetField)
            ->label('Bild hochladen')
            ->tooltip('Neues Bild von Ihrem Gerät hochladen')
            ->icon(Heroicon::OutlinedArrowUpTray)
            ->color('gray')
            ->modalHeading('Seitenbild hochladen')
            ->modalDescription('Laden Sie ein Foto oder eine Grafik (JPG, PNG, WebP) hoch. EXIF-/Standortdaten werden automatisch DSGVO-konform entfernt.')
            ->modalSubmitActionLabel('Bild übernehmen')
            ->schema([
                FileUpload::make('uploaded_image')
                    ->label('Bilddatei auswählen')
                    ->disk('public')
                    ->directory('pages')
                    ->image()
                    ->imageEditor()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(10240)
                    ->required(),
            ])
            ->action(function (array $data, Set $set) use ($targetField): void {
                $relativePath = isset($data['uploaded_image']) && is_string($data['uploaded_image'])
                    ? $data['uploaded_image']
                    : null;

                if ($relativePath === null || $relativePath === '') {
                    return;
                }

                $absolutePath = Storage::disk('public')->path($relativePath);
                if (is_file($absolutePath)) {
                    ImageExifStripper::stripFileInPlace($absolutePath);
                }

                $set($targetField, '/storage/'.ltrim($relativePath, '/'));
            });
    }
}
