<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageSchoolSettings extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string | UnitEnum | null $navigationGroup = 'System & Verwaltung';

    protected static ?string $navigationLabel = 'Schuleinstellungen';

    protected static ?string $title = 'Schuleinstellungen & Kontaktdaten';

    protected static ?int $navigationSort = 2;

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
        /** @var array<string, mixed> $defaults */
        $defaults = [
            'name' => 'Mühlmattenschule',
            'subtitle' => 'Grundschule Freiburg-Hochdorf',
            'street' => 'Zu den Mühlmatten 8',
            'zip_city' => '79108 Freiburg im Breisgau',
            'phone' => '0761 / 201-7612',
            'email' => 'sekretariat.mms@freiburg.de',
            'office_hours' => 'Mo–Fr 07:45–12:00 Uhr',
            'welcome_title' => 'Willkommen an der Mühlmattenschule',
            'welcome_text' => 'Grundschule für die Freiburger Stadtteile Hochdorf und Benzhausen.',
        ];

        /** @var array<string, mixed> $saved */
        $saved = Setting::getValue('school', []);

        $this->form->fill(array_merge($defaults, $saved));
    }

    /**
     * Definiert das Einstellungsformular.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Stammdaten & Kontakt')
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
                            ->label('Telefonnummer')
                            ->maxLength(80),
                        TextInput::make('email')
                            ->label('E-Mail-Adresse')
                            ->email()
                            ->maxLength(160),
                        TextInput::make('office_hours')
                            ->label('Öffnungszeiten Sekretariat')
                            ->maxLength(160)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

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

        Setting::setValue('school', $state);

        Notification::make()
            ->title('Schuleinstellungen wurden gespeichert.')
            ->success()
            ->send();
    }
}

