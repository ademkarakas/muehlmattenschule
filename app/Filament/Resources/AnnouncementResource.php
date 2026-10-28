<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\AnnouncementLevel;
use App\Filament\Resources\AnnouncementResource\Pages\ManageAnnouncements;
use App\Models\Announcement;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
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

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string | UnitEnum | null $navigationGroup = 'Aktuelles & Schulleben';

    protected static ?string $navigationLabel = 'Ankündigungen';

    protected static ?string $modelLabel = 'Ankündigung';

    protected static ?string $pluralModelLabel = 'Ankündigungen';

    protected static ?int $navigationSort = 4;

    /**
     * Definiert das Formular für eine Startseiten-Ankündigung.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('text')
                    ->label('Mitteilungstext')
                    ->required()
                    ->maxLength(500)
                    ->rows(3)
                    ->columnSpanFull(),
                Select::make('level')
                    ->label('Wichtigkeit')
                    ->options(AnnouncementLevel::options())
                    ->default(AnnouncementLevel::Info->value)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktiv geschaltet')
                    ->default(true),
                TextInput::make('link_label')
                    ->label('Link-Beschriftung (optional)')
                    ->placeholder('z. B. Weitere Informationen')
                    ->maxLength(100),
                TextInput::make('link_url')
                    ->label('Zieladresse / URL (optional)')
                    ->placeholder('z. B. /aktuelles oder /downloads')
                    ->maxLength(300),
                DateTimePicker::make('visible_from')
                    ->label('Sichtbar ab (optional)')
                    ->seconds(false),
                DateTimePicker::make('visible_until')
                    ->label('Automatisch ausblenden am (optional)')
                    ->seconds(false),
            ]);
    }

    /**
     * Definiert die Tabellenansicht der Ankündigungen.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('text')
                    ->label('Mitteilung')
                    ->limit(70)
                    ->searchable(),
                TextColumn::make('level')
                    ->label('Stufe')
                    ->badge()
                    ->formatStateUsing(fn (AnnouncementLevel $state): string => $state->label())
                    ->color(fn (AnnouncementLevel $state): string => $state === AnnouncementLevel::Important ? 'warning' : 'primary'),
                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
                TextColumn::make('visible_until')
                    ->label('Sichtbar bis')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('Unbegrenzt'),
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
            'index' => ManageAnnouncements::route('/'),
        ];
    }
}
