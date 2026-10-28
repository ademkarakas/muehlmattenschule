<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Alle angemeldeten Mitarbeiter (inkl. Lehrkräfte) sehen die Beitragsliste.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Einzelansicht eines Beitrags im Admin-Panel.
     */
    public function view(User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Alle Rollen (auch Lehrkräfte) dürfen Beiträge für die Schule oder ihre Klasse anlegen.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Büroteam darf alle Beiträge bearbeiten, Lehrkräfte nur ihre eigenen.
     */
    public function update(User $user, Post $post): bool
    {
        if ($user->isOfficeStaff()) {
            return true;
        }

        return $post->author_id === $user->id;
    }

    /**
     * Büroteam darf alle Beiträge löschen, Lehrkräfte nur ihre eigenen.
     */
    public function delete(User $user, Post $post): bool
    {
        if ($user->isOfficeStaff()) {
            return true;
        }

        return $post->author_id === $user->id;
    }

    /**
     * Mehrfachlöschung ist dem Büroteam vorbehalten.
     */
    public function deleteAny(User $user): bool
    {
        return $user->isOfficeStaff();
    }
}
