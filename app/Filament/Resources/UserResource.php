<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\UserRole;
use App\Filament\AvatarProviders\LocalInitialsAvatarProvider;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'System & Verwaltung';

    protected static ?string $navigationLabel = 'Benutzer & Lehrkräfte';

    protected static ?string $modelLabel = 'Benutzerkonto';

    protected static ?string $pluralModelLabel = 'Benutzerkonten';

    protected static ?int $navigationSort = 1;

    /**
     * Definiert das Formular für Benutzerkonten (Schulleitung, Sekretariat, Lehrkräfte).
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Benutzerkonto & Zugangsdaten')
                    ->icon(Heroicon::OutlinedUsers)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Vollständiger Name')
                            ->placeholder('z. B. Diana Blum')
                            ->prefixIcon(Heroicon::OutlinedUser)
                            ->required()
                            ->maxLength(160),
                        TextInput::make('email')
                            ->label('E-Mail-Adresse (Anmeldename)')
                            ->placeholder('vorname.nachname@muehlmattenschule.de')
                            ->prefixIcon(Heroicon::OutlinedEnvelope)
                            ->email()
                            ->required()
                            ->maxLength(160)
                            ->unique(ignoreRecord: true),
                        ...static::roleFields(),
                        TextInput::make('password')
                            ->label('Passwort')
                            ->prefixIcon(Heroicon::OutlinedKey)
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->rule(Password::default())
                            ->helperText('Mindestens 12 Zeichen mit Groß-/Kleinbuchstaben, Zahl und Sonderzeichen. Beim Bearbeiten leer lassen, um das bestehende Passwort zu behalten.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * Felder für Rolle und Klassenzuordnung.
     *
     * @return array<int, Select|TextInput>
     */
    protected static function roleFields(): array
    {
        return [
            Select::make('role')
                ->label('Rolle & Berechtigung')
                ->prefixIcon(Heroicon::OutlinedUserGroup)
                ->options(function (): array {
                    $options = UserRole::options();
                    if (auth()->user()?->role !== UserRole::Admin) {
                        unset($options[UserRole::Admin->value]);
                    }

                    return $options;
                })
                ->native(false)
                ->default(UserRole::Lehrkraft->value)
                ->required(),
            TextInput::make('class_name')
                ->label('Zugeordnete Klasse (für Lehrkräfte)')
                ->placeholder('z. B. Klasse 2a')
                ->prefixIcon(Heroicon::OutlinedAcademicCap)
                ->maxLength(60),
        ];
    }

    /**
     * Definiert die Tabellenansicht der Benutzerkonten.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('')
                    ->state(fn (User $record): string => app(LocalInitialsAvatarProvider::class)->get($record))
                    ->square()
                    ->imageSize(36)
                    ->grow(false),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn (User $record): string => collect([$record->email, $record->class_name])->filter()->implode(' · ')),
                TextColumn::make('role')
                    ->label('Rolle')
                    ->badge()
                    ->formatStateUsing(fn (UserRole $state): string => $state->label()),
            ])
            ->recordUrl(fn (User $record): ?string => auth()->user()?->can('update', $record)
                ? static::getUrl('edit', ['record' => $record])
                : null)
            ->searchPlaceholder('Name, E-Mail oder Klasse suchen...')
            ->emptyStateIcon(Heroicon::OutlinedUsers)
            ->emptyStateHeading('Keine Benutzerkonten gefunden')
            ->emptyStateDescription('Legen Sie ein neues Konto für Schulleitung, Sekretariat oder Lehrkräfte an.')
            ->recordActions([
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
