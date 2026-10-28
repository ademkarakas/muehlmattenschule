<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    /**
     * Ankündigungen werden nur vom Büroteam (Schulleitung, Sekretariat, Admin) verwaltet.
     */
    public function viewAny(User $user): bool
    {
        return $user->isOfficeStaff();
    }

    public function view(User $user, Announcement $announcement): bool
    {
        return $user->isOfficeStaff();
    }

    public function create(User $user): bool
    {
        return $user->isOfficeStaff();
    }

    public function update(User $user, Announcement $announcement): bool
    {
        return $user->isOfficeStaff();
    }

    public function delete(User $user, Announcement $announcement): bool
    {
        return $user->isOfficeStaff();
    }
}

