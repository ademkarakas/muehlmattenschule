<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DownloadResource\Pages\CreateDownload;
use App\Filament\Resources\DownloadResource\Pages\EditDownload;
use App\Filament\Resources\DownloadResource\Pages\ListDownloads;
use App\Models\Download;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class DownloadResource extends Resource
{
    protected static ?string $model = Download::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowDownTray;

    protected static string|UnitEnum|null $navigationGroup = 'Schulstruktur & Seiten';

    protected static ?string $navigationLabel = 'Downloads & Elternbriefe';

    protected static ?string $modelLabel = 'Download';

    protected static ?string $pluralModelLabel = 'Downloads';

    protected static ?int $navigationSort = 3;

    /**
     * Definiert das Formular für herunterladbare Dokumente (PDFs, Formulare, Elternbriefe).
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dokument & Datei-Upload')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Titel des Dokuments')
                            ->prefixIcon(Heroicon::OutlinedDocumentText)
                            ->required()
                            ->maxLength(200),
                        Select::make('category')
                            ->label('Bereich')
                            ->prefixIcon(Heroicon::OutlinedFolderOpen)
                            ->options([
                                'Elternbriefe' => 'Elternbriefe',
                                'Formulare' => 'Formulare & Anträge',
                                'Betreuung' => 'Schulkindbetreuung',
                                'Sonstiges' => 'Sonstiges',
                            ])
                            ->native(false)
                            ->default('Formulare')
                            ->required(),
                        Textarea::make('description')
                            ->label('Kurzbeschreibung (optional)')
                            ->rows(2)
                            ->maxLength(400)
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('file')
                            ->label('Datei (PDF / Dokument)')
                            ->collection('file')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ])
                            ->maxSize(15360)
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('Reihenfolge')
                            ->prefixIcon(Heroicon::OutlinedArrowsUpDown)
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_published')
                            ->label('Veröffentlicht')
                            ->default(true),
                    ]),
            ]);
    }

    /**
     * Definiert die Tabellenansicht der Downloads.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('medium')
                    ->description(fn (Download $record): string => $record->category.' · '.$record->updated_at?->format('d.m.Y')),
                IconColumn::make('is_published')
                    ->label('Sichtbar')
                    ->boolean(),
            ])
            ->recordUrl(fn (Download $record): ?string => auth()->user()?->can('update', $record)
                ? static::getUrl('edit', ['record' => $record])
                : null)
            ->searchPlaceholder('Dokumenttitel suchen...')
            ->emptyStateIcon(Heroicon::OutlinedArrowDownTray)
            ->emptyStateHeading('Keine Downloads gefunden')
            ->emptyStateDescription('Laden Sie über „Neu“ einen Elternbrief oder ein Formular hoch.')
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
            'index' => ListDownloads::route('/'),
            'create' => CreateDownload::route('/create'),
            'edit' => EditDownload::route('/{record}/edit'),
        ];
    }
}
