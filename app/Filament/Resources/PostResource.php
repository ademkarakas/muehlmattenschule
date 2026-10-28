<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\PublishStatus;
use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Models\Post;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string | UnitEnum | null $navigationGroup = 'Aktuelles & Schulleben';

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
            ->components([
                Section::make('Inhalt des Beitrags')
                    ->schema([
                        TextInput::make('title')
                            ->label('Überschrift')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('slug', Str::slug((string) $state))),
                        TextInput::make('slug')
                            ->label('Kurz-URL (Slug)')
                            ->required()
                            ->maxLength(220)
                            ->unique(ignoreRecord: true),
                        Select::make('category_id')
                            ->label('Kategorie')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('class_name')
                            ->label('Klasse (optional, z. B. Klasse 2a)')
                            ->default(fn (): ?string => auth()->user()?->class_name)
                            ->maxLength(60),
                        Textarea::make('excerpt')
                            ->label('Kurzfassung (Vorschautext auf der Startseite)')
                            ->rows(2)
                            ->maxLength(400)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->label('Bericht / Text')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Titelbild, Fotoalbum & Veröffentlichung')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->label('Titelbild')
                            ->collection('cover')
                            ->image()
                            ->maxSize(10240)
                            ->columnSpanFull(),
                        Select::make('album_id')
                            ->label('Verknüpftes Fotoalbum (optional)')
                            ->relationship('album', 'title')
                            ->searchable()
                            ->preload(),
                        Toggle::make('is_featured')
                            ->label('Auf der Startseite oben hervorheben')
                            ->default(false),
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
     * Definiert die Tabellenansicht der Beiträge.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label('Überschrift')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('category.name')
                    ->label('Kategorie')
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('class_name')
                    ->label('Klasse')
                    ->badge()
                    ->placeholder('Schule'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (PublishStatus $state): string => $state->label())
                    ->color(fn (PublishStatus $state): string => $state === PublishStatus::Published ? 'success' : 'gray'),
                IconColumn::make('is_featured')
                    ->label('Startseite')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Datum')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label('Verfasser')
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(PublishStatus::options()),
                SelectFilter::make('category')
                    ->label('Kategorie')
                    ->relationship('category', 'name'),
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
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
