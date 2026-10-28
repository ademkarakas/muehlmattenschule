<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Attribute, die automatisch gecastet werden.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * Prüft, ob der Benutzer auf das Filament-Admin-Panel zugreifen darf.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, [
            UserRole::Admin,
            UserRole::Schulleitung,
            UserRole::Sekretariat,
        ], true);
    }

    /**
     * Prüft, ob der Benutzer Administrator oder Schulleitung ist.
     */
    public function isAdminOrLeadership(): bool
    {
        return in_array($this->role, [UserRole::Admin, UserRole::Schulleitung], true);
    }

    /**
     * Beiträge, die von diesem Benutzer verfasst wurden.
     *
     * @return HasMany<Post, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }
}
