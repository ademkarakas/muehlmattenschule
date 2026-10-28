<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Das aktuell für Testbenutzer verwendete Passwort.
     */
    protected static ?string $password;

    /**
     * Definiert den Standardzustand des Modells.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => UserRole::Sekretariat,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Markiert die E-Mail-Adresse als unbestätigt.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Erstellt einen Administrator-Benutzer.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => UserRole::Admin,
        ]);
    }

    /**
     * Erstellt einen Schulleitungs-Benutzer.
     */
    public function schulleitung(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => UserRole::Schulleitung,
        ]);
    }

    /**
     * Erstellt einen Lehrkraft-Benutzer für eine bestimmte Klasse.
     */
    public function lehrkraft(string $className = 'Klasse 2a'): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => UserRole::Lehrkraft,
            'class_name' => $className,
        ]);
    }
}
