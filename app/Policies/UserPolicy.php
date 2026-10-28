<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    /**
     * Benutzerverwaltung ist nur für Administration und Schulleitung sichtbar.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdminOrLeadership();
    }

    public function view(User $user, User $model): bool
    {
        return $user->isAdminOrLeadership();
    }

    public function create(User $user): bool
    {
        return $user->isAdminOrLeadership();
    }

    /**
     * Schulleitung darf keine Administrator-Konten bearbeiten.
     */
    public function update(User $user, User $model): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Schulleitung && $model->role !== UserRole::Admin;
    }

    /**
     * Ein Benutzer kann sich nicht selbst löschen; Schulleitung kann keine Administratoren löschen.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }

        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Schulleitung && $model->role !== UserRole::Admin;
    }
}

