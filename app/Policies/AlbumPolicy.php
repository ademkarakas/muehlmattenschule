<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Album;
use App\Models\User;

class AlbumPolicy
{
    /**
     * Alle angemeldeten Mitarbeiter (inkl. Lehrkräfte) sehen die Fotoalben-Liste.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Einzelansicht eines Fotoalbums im Admin-Panel.
     */
    public function view(User $user, Album $album): bool
    {
        return true;
    }

    /**
     * Alle Rollen (auch Lehrkräfte) dürfen Fotoalben anlegen.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Büroteam darf alle Alben bearbeiten, Lehrkräfte nur ihre eigenen.
     */
    public function update(User $user, Album $album): bool
    {
        if ($user->isOfficeStaff()) {
            return true;
        }

        return $album->author_id === $user->id;
    }

    /**
     * Büroteam darf alle Alben löschen, Lehrkräfte nur ihre eigenen.
     */
    public function delete(User $user, Album $album): bool
    {
        if ($user->isOfficeStaff()) {
            return true;
        }

        return $album->author_id === $user->id;
    }

    /**
     * Mehrfachlöschung ist dem Büroteam vorbehalten.
     */
    public function deleteAny(User $user): bool
    {
        return $user->isOfficeStaff();
    }
}

