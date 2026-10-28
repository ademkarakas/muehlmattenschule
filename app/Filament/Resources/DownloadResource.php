<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DownloadResource\Pages\ManageDownloads;
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
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class DownloadResource extends Resource
{
    protected static ?string $model = Download::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedArrowDownTray;

    protected static string | UnitEnum | null $navigationGroup = 'Schulstruktur & Seiten';

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
                TextInput::make('title')
                    ->label('Titel des Dokuments')
                    ->required()
                    ->maxLength(200),
                Select::make('category')
                    ->label('Bereich')
                    ->options([
                        'Elternbriefe' => 'Elternbriefe',
                        'Formulare' => 'Formulare & Anträge',
                        'Betreuung' => 'Schulkindbetreuung',
                        'Sonstiges' => 'Sonstiges',
                    ])
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
                    ->numeric()
                    ->default(0),
                Toggle::make('is_published')
                    ->label('Veröffentlicht')
                    ->default(true),
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
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Bereich')
                    ->badge(),
                IconColumn::make('is_published')
                    ->label('Sichtbar')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->date('d.m.Y'),
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
            'index' => ManageDownloads::route('/'),
        ];
    }
}

