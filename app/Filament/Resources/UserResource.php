<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\UserRole;
use App\Filament\Resources\UserResource\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
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
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(160),
                TextInput::make('email')
                    ->label('E-Mail-Adresse (Anmeldename)')
                    ->email()
                    ->required()
                    ->maxLength(160)
                    ->unique(ignoreRecord: true),
                Select::make('role')
                    ->label('Rolle & Berechtigung')
                    ->options(function (): array {
                        $options = UserRole::options();
                        if (auth()->user()?->role !== UserRole::Admin) {
                            unset($options[UserRole::Admin->value]);
                        }

                        return $options;
                    })
                    ->default(UserRole::Lehrkraft->value)
                    ->required(),
                TextInput::make('class_name')
                    ->label('Zugeordnete Klasse (für Lehrkräfte)')
                    ->placeholder('z. B. Klasse 2a')
                    ->maxLength(60),
                TextInput::make('password')
                    ->label('Passwort')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->minLength(8)
                    ->helperText('Mindestens 8 Zeichen. Beim Bearbeiten leer lassen, um das bestehende Passwort zu behalten.'),
            ]);
    }

    /**
     * Definiert die Tabellenansicht der Benutzerkonten.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable(),
                TextColumn::make('role')
                    ->label('Rolle')
                    ->badge()
                    ->formatStateUsing(fn (UserRole $state): string => $state->label()),
                TextColumn::make('class_name')
                    ->label('Klasse')
                    ->placeholder('—'),
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
            'index' => ManageUsers::route('/'),
        ];
    }
}
