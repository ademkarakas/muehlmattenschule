<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\AnnouncementLevel;
use App\Filament\Resources\AnnouncementResource\Pages\CreateAnnouncement;
use App\Filament\Resources\AnnouncementResource\Pages\EditAnnouncement;
use App\Filament\Resources\AnnouncementResource\Pages\ListAnnouncements;
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
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $recordTitleAttribute = 'text';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|UnitEnum|null $navigationGroup = 'Aktuelles & Schulleben';

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
                Section::make('Mitteilung & Anzeigezeitraum')
                    ->icon(Heroicon::OutlinedMegaphone)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Textarea::make('text')
                            ->label('Mitteilungstext')
                            ->required()
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),
                        Select::make('level')
                            ->label('Wichtigkeit')
                            ->prefixIcon(Heroicon::OutlinedMegaphone)
                            ->options(AnnouncementLevel::options())
                            ->native(false)
                            ->default(AnnouncementLevel::Info->value)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Als Pop-up beim Website-Besuch anzeigen (Aktiv)')
                            ->helperText('Wenn aktiviert, öffnet sich diese Ankündigung beim Betreten der Website als zentriertes Hinweisfenster (schließbar per × oder Esc).')
                            ->default(true),
                        TextInput::make('link_label')
                            ->label('Link-Beschriftung (optional)')
                            ->placeholder('z. B. Weitere Informationen')
                            ->prefixIcon(Heroicon::OutlinedTag)
                            ->maxLength(100),
                        TextInput::make('link_url')
                            ->label('Zieladresse / URL (optional)')
                            ->placeholder('z. B. /aktuelles oder /downloads')
                            ->prefixIcon(Heroicon::OutlinedLink)
                            ->maxLength(300),
                        DateTimePicker::make('visible_from')
                            ->label('Sichtbar ab (optional)')
                            ->prefixIcon(Heroicon::OutlinedCalendarDays)
                            ->seconds(false),
                        DateTimePicker::make('visible_until')
                            ->label('Automatisch ausblenden am (optional)')
                            ->prefixIcon(Heroicon::OutlinedClock)
                            ->seconds(false),
                    ]),
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
                    ->lineClamp(2)
                    ->wrap()
                    ->searchable()
                    ->description(fn (Announcement $record): string => $record->visible_until
                        ? 'Bis '.$record->visible_until->format('d.m.Y H:i')
                        : 'Unbegrenzt'),
                TextColumn::make('level')
                    ->label('Stufe')
                    ->badge()
                    ->formatStateUsing(fn (AnnouncementLevel $state): string => $state->label())
                    ->color(fn (AnnouncementLevel $state): string => $state === AnnouncementLevel::Important ? 'warning' : 'primary'),
                ToggleColumn::make('is_active')
                    ->label('Aktiv'),
            ])
            ->recordUrl(fn (Announcement $record): ?string => auth()->user()?->can('update', $record)
                ? static::getUrl('edit', ['record' => $record])
                : null)
            ->searchPlaceholder('Mitteilungstext suchen...')
            ->emptyStateIcon(Heroicon::OutlinedMegaphone)
            ->emptyStateHeading('Keine Ankündigungen gefunden')
            ->emptyStateDescription('Erstellen Sie eine neue Ankündigung, die Besuchern beim Öffnen der Website als zentriertes Hinweisfenster angezeigt wird.')
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
            'index' => ListAnnouncements::route('/'),
            'create' => CreateAnnouncement::route('/create'),
            'edit' => EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
