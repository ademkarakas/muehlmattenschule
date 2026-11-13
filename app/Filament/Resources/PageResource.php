<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\PersonGroup;
use App\Enums\PublishStatus;
use App\Filament\Resources\PageResource\Pages\CreatePage;
use App\Filament\Resources\PageResource\Pages\EditPage;
use App\Filament\Resources\PageResource\Pages\ListPages;
use App\Models\Page;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
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
                                    ->label('Textabschnitt')
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Zwischenüberschrift (optional)')
                                            ->maxLength(160),
                                        RichEditor::make('body')
                                            ->label('Text')
                                            ->required(),
                                    ]),
                                Block::make('info_box')
                                    ->label('Infokasten / Hinweis')
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
                                    ->schema([
                                        TextInput::make('image_url')
                                            ->label('Bild-Pfad oder URL')
                                            ->placeholder('/images/school/about-main.jpg')
                                            ->required(),
                                        TextInput::make('caption')
                                            ->label('Bildunterschrift (optional)')
                                            ->maxLength(200),
                                    ]),
                                Block::make('image_gallery')
                                    ->label('Fotoreihe (2–3 Bilder)')
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Überschrift (optional)')
                                            ->maxLength(160),
                                        TextInput::make('image_1_url')
                                            ->label('Bild 1 URL')
                                            ->required(),
                                        TextInput::make('image_1_caption')
                                            ->label('Bild 1 Unterschrift'),
                                        TextInput::make('image_2_url')
                                            ->label('Bild 2 URL'),
                                        TextInput::make('image_2_caption')
                                            ->label('Bild 2 Unterschrift'),
                                        TextInput::make('image_3_url')
                                            ->label('Bild 3 URL'),
                                        TextInput::make('image_3_caption')
                                            ->label('Bild 3 Unterschrift'),
                                    ]),
                                Block::make('people_list')
                                    ->label('Personenliste (Kollegium / Leitung)')
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
}
