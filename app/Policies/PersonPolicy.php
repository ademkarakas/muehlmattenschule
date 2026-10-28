<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Person;
use App\Models\User;

class PersonPolicy
{
    /**
     * Das Kollegium und die Verwaltung werden von Schulleitung, Sekretariat und Admin gepflegt.
     */
    public function viewAny(User $user): bool
    {
        return $user->isOfficeStaff();
    }

    public function view(User $user, Person $person): bool
    {
        return $user->isOfficeStaff();
    }

    public function create(User $user): bool
    {
        return $user->isOfficeStaff();
    }

    public function update(User $user, Person $person): bool
    {
        return $user->isOfficeStaff();
    }

    public function delete(User $user, Person $person): bool
    {
        return $user->isAdminOrLeadership();
    }
}

