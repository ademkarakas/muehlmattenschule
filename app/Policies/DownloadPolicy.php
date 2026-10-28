<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Download;
use App\Models\User;

class DownloadPolicy
{
    /**
     * Downloads (Elternbriefe, Formulare) werden vom Büroteam verwaltet.
     */
    public function viewAny(User $user): bool
    {
        return $user->isOfficeStaff();
    }

    public function view(User $user, Download $download): bool
    {
        return $user->isOfficeStaff();
    }

    public function create(User $user): bool
    {
        return $user->isOfficeStaff();
    }

    public function update(User $user, Download $download): bool
    {
        return $user->isOfficeStaff();
    }

    public function delete(User $user, Download $download): bool
    {
        return $user->isOfficeStaff();
    }
}
