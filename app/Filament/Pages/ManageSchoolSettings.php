<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\SeoData;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageSchoolSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'System & Verwaltung';

    protected static ?string $navigationLabel = 'Schuleinstellungen';

    protected static ?string $title = 'Schuleinstellungen, Startseite & Wartungsmodus';

    protected static ?int $navigationSort = 2;

    /**
     * Dezent-solider Untertitel unter der Seitenüberschrift.
     */
    public function getSubheading(): ?string
    {
        return 'Stammdaten, Kontaktinformationen, Startseiten-Elemente, Unterrichtszeiten und Wartungsmodus der Mühlmattenschule verwalten.';
    }

    /**
     * Fügt die Design-Klasse für den helltürkisen Kopfbereich und das Karten-Layout hinzu.
     *
     * @return array<int, string>
     */
    public function getPageClasses(): array
    {
        return [
            ...parent::getPageClasses(),
            'mms-settings-page',
        ];
    }

    /**
     * Formularzustand für die Schuleinstellungen.
     *
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    /**
     * Nur Administration und Schulleitung dürfen die Schuleinstellungen bearbeiten.
     */
    public static function canAccess(): bool
    {
        return auth()->user()?->isAdminOrLeadership() ?? false;
    }

    /**
     * Lädt die gespeicherten Einstellungen beim Öffnen der Seite.
     */
    public function mount(): void
    {
        $this->form->fill(SeoData::schoolSettings());
    }

    /**
     * Schnell-Aktion oben rechts zum direkten Ein-/Ausschalten des Wartungsmodus.
     *
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        $isActive = (bool) ($this->data['maintenance_active'] ?? false);

        return [
            Action::make('previewMaintenancePage')
                ->label('Wartungsseite ansehen')
                ->color('gray')
                ->icon(Heroicon::OutlinedEye)
                ->url(url('/?wartungsmodus-vorschau=1'), shouldOpenInNewTab: true)
                ->visible($isActive),
            Action::make('toggleMaintenance')
                ->label($isActive ? 'Wartungsmodus beenden (Website live schalten)' : 'Wartungsmodus aktivieren')
                ->color($isActive ? 'success' : 'warning')
                ->icon($isActive ? Heroicon::OutlinedCheckCircle : Heroicon::OutlinedWrenchScrewdriver)
                ->requiresConfirmation()
                ->modalHeading($isActive ? 'Wartungsmodus beenden?' : 'Website in den Wartungsmodus versetzen?')
                ->modalDescription(
                    $isActive
                        ? 'Die öffentliche Website ist danach sofort wieder für alle Besucherinnen und Besucher zugänglich.'
                        : 'Besucherinnen und Besucher sehen sofort eine freundliche Informationsseite (HTTP 503) mit den Kontaktdaten des Sekretariats. Angemeldete Mitarbeitende sehen auf der Website einen Hinweisbalken und können sie weiterhin als Vorschau prüfen.'
                )
                ->action(function (): void {
                    $current = SeoData::schoolSettings();
                    $newState = ! $current['maintenance_active'];
                    $current['maintenance_active'] = $newState;
                    Setting::setValue('school', $current);
                    $this->form->fill($current);

                    Notification::make()
                        ->title($newState ? 'Wartungsmodus wurde aktiviert.' : 'Wartungsmodus wurde beendet – Website ist live.')
                        ->color($newState ? 'warning' : 'success')
                        ->send();

                    $this->redirect(static::getUrl());
                }),
        ];
    }

    /**
     * Definiert das Einstellungsformular mit übersichtlichen Registern (Tabs).
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('Einstellungen')
                    ->tabs([
                        Tab::make('Stammdaten & Kontakt')
                            ->icon(Heroicon::OutlinedBuildingLibrary)
                            ->schema([
                                Section::make('Schuladresse & Sekretariat')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Schulname')
                                            ->required()
                                            ->maxLength(160),
                                        TextInput::make('subtitle')
                                            ->label('Zusatz / Untertitel')
                                            ->maxLength(160),
                                        TextInput::make('street')
                                            ->label('Straße & Hausnummer')
                                            ->required()
                                            ->maxLength(160),
                                        TextInput::make('zip_city')
                                            ->label('PLZ & Ort')
                                            ->required()
                                            ->maxLength(160),
                                        TextInput::make('phone')
                                            ->label('Telefonnummer Sekretariat')
                                            ->maxLength(80),
                                        TextInput::make('fax')
                                            ->label('Telefax')
                                            ->maxLength(80),
                                        TextInput::make('email')
                                            ->label('E-Mail-Adresse Sekretariat')
                                            ->email()
                                            ->maxLength(160),
                                        TextInput::make('office_hours')
                                            ->label('Öffnungszeiten Sekretariat')
                                            ->maxLength(160),
                                    ])
                                    ->columns(2),

                                Section::make('Schulkindbetreuung (Caritas) Notfall- & Kontaktdaten')
                                    ->schema([
                                        TextInput::make('betreuung_phone')
                                            ->label('Festnetz Betreuung')
                                            ->maxLength(80),
                                        TextInput::make('betreuung_mobile')
                                            ->label('Mobilnummer Betreuung')
                                            ->maxLength(80),
                                        TextInput::make('betreuung_email')
                                            ->label('E-Mail-Adresse Betreuung')
                                            ->email()
                                            ->maxLength(160)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),

                        Tab::make('Startseite & Hero-Bilder')
                            ->icon(Heroicon::OutlinedPhoto)
                            ->schema([
                                Section::make('Startseiten-Begrüßung')
                                    ->schema([
                                        TextInput::make('welcome_title')
                                            ->label('Begrüßungs-Überschrift')
                                            ->maxLength(200),
                                        Textarea::make('welcome_text')
                                            ->label('Begrüßungstext')
                                            ->rows(3)
                                            ->maxLength(500),
                                    ]),

                                Section::make('Hero-Fotogalerie auf der Startseite')
                                    ->description('Diese Schulfotos werden oben rechts im Begrüßungsbereich der Startseite als wischbare Galerie angezeigt.')
                                    ->schema([
                                        Repeater::make('hero_slides')
                                            ->label('Schulfotos im Kopfbereich')
                                            ->addActionLabel('Weiteres Foto hinzufügen')
                                            ->schema([
                                                TextInput::make('url')
                                                    ->label('Bild-Pfad (z. B. /images/school/about-main.jpg)')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('alt')
                                                    ->label('Bildbeschreibung (Alternativtext für Barrierefreiheit)')
                                                    ->required()
                                                    ->maxLength(255),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(3)
                                            ->reorderable(),
                                    ]),
                            ]),

                        Tab::make('Unterrichts- & Pausenzeiten')
                            ->icon(Heroicon::OutlinedClock)
                            ->schema([
                                Section::make('Stunden- & Pausenraster (Startseiten-Widget „Pausen & Unterrichtszeiten“)')
                                    ->description('Wird wie bei ghsfreiburg.edupage.org in der rechten Spalte der Startseite angezeigt.')
                                    ->schema([
                                        Repeater::make('bell_schedule')
                                            ->label('Zeitabschnitte')
                                            ->addActionLabel('Zeitabschnitt hinzufügen')
                                            ->schema([
                                                TextInput::make('time')
                                                    ->label('Uhrzeit (z. B. 07:50 – 08:35)')
                                                    ->required()
                                                    ->maxLength(40),
                                                TextInput::make('label')
                                                    ->label('Bezeichnung (z. B. 1. Unterrichtsstunde / Große Pause)')
                                                    ->required()
                                                    ->maxLength(120),
                                                Select::make('type')
                                                    ->label('Art')
                                                    ->options([
                                                        'lesson' => 'Unterricht',
                                                        'break' => 'Pause',
                                                        'betreuung' => 'Betreuung',
                                                    ])
                                                    ->default('lesson')
                                                    ->required(),
                                            ])
                                            ->columns(3)
                                            ->reorderable(),
                                    ]),
                            ]),

                        Tab::make('Wartungsmodus')
                            ->icon(Heroicon::OutlinedWrenchScrewdriver)
                            ->schema([
                                Section::make('Website in den Wartungsmodus versetzen')
                                    ->description('Wenn der Wartungsmodus aktiviert ist, sehen Besucherinnen und Besucher eine freundliche Informationsseite im Mühlmattenschule-Design mit allen wichtigen Kontaktdaten (Sekretariat, Krankmeldung, Schulkindbetreuung). Angemeldete Mitarbeitende können die Website weiterhin normal als Vorschau betrachten.')
                                    ->schema([
                                        Toggle::make('maintenance_active')
                                            ->label('Wartungsmodus aktivieren (Öffentliche Website vorübergehend sperren)')
                                            ->helperText('Das Admin-Panel (/admin) bleibt jederzeit erreichbar.')
                                            ->default(false)
                                            ->columnSpanFull(),
                                        TextInput::make('maintenance_title')
                                            ->label('Überschrift auf der Wartungsseite')
                                            ->placeholder('Unsere Website wird gerade für Sie aktualisiert')
                                            ->required()
                                            ->maxLength(200)
                                            ->columnSpanFull(),
                                        Textarea::make('maintenance_message')
                                            ->label('Erläuternder Hinweistext für Eltern und Besucher')
                                            ->rows(3)
                                            ->required()
                                            ->maxLength(800)
                                            ->columnSpanFull(),
                                        TextInput::make('maintenance_until')
                                            ->label('Voraussichtlich wieder erreichbar (optional)')
                                            ->placeholder('z. B. Heute ab ca. 15:00 Uhr oder Montag, 08:00 Uhr')
                                            ->maxLength(160),
                                        Toggle::make('maintenance_show_contact')
                                            ->label('Notfall- & Kontaktdaten (Sekretariat, Krankmeldung & Schulkindbetreuung) anzeigen')
                                            ->default(true),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Baut den Seiteninhalt mit eingebettetem Formular und Speichern-Schaltfläche.
     */
    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Einstellungen speichern')
                                ->submit('save'),
                        ]),
                    ]),
            ]);
    }

    /**
     * Speichert die aktualisierten Schuleinstellungen in der Datenbank.
     */
    public function save(): void
    {
        /** @var array<string, mixed> $state */
        $state = $this->form->getState();

        $previous = SeoData::schoolSettings();
        $merged = array_merge($previous, $state);
        Setting::setValue('school', $merged);

        Notification::make()
            ->title('Schuleinstellungen wurden gespeichert.')
            ->success()
            ->send();

        if ((bool) $previous['maintenance_active'] !== (bool) ($merged['maintenance_active'] ?? false)) {
            $this->redirect(static::getUrl());
        }
    }
}
