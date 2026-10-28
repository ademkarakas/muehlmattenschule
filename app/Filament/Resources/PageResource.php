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
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
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
                    ->schema([
                        TextInput::make('title')
                            ->label('Seitentitel')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('slug', Str::slug((string) $state))),
                        TextInput::make('slug')
                            ->label('Kurz-URL (Slug)')
                            ->required()
                            ->maxLength(220)
                            ->unique(ignoreRecord: true),
                        Select::make('section')
                            ->label('Menübereich')
                            ->options([
                                'unsere-schule' => 'Unsere Schule',
                                'schulleben' => 'Schulleben & Betreuung',
                            ])
                            ->default('unsere-schule')
                            ->required(),
                        TextInput::make('menu_order')
                            ->label('Reihenfolge im Menü')
                            ->numeric()
                            ->default(1),
                        Select::make('status')
                            ->label('Status')
                            ->options(PublishStatus::options())
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
                                Block::make('people_list')
                                    ->label('Personenliste (Kollegium / Leitung)')
                                    ->schema([
                                        Select::make('group')
                                            ->label('Anzuzeigende Gruppe')
                                            ->options([
                                                'all' => 'Alle Gruppen',
                                                ...PersonGroup::options(),
                                            ])
                                            ->default('all')
                                            ->required(),
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
                    ->sortable(),
                TextColumn::make('section')
                    ->label('Menübereich')
                    ->badge(),
                TextColumn::make('menu_order')
                    ->label('Reihenfolge')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (PublishStatus $state): string => $state->label())
                    ->color(fn (PublishStatus $state): string => $state === PublishStatus::Published ? 'success' : 'gray'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
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
