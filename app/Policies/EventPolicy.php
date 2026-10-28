<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Alle Rollen sehen die Terminliste.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Einzelansicht eines Termins.
     */
    public function view(User $user, Event $event): bool
    {
        return true;
    }

    /**
     * Alle Rollen (inkl. Lehrkräfte) dürfen Termine eintragen.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Termine dürfen von allen Rollen gepflegt werden.
     */
    public function update(User $user, Event $event): bool
    {
        return true;
    }

    /**
     * Löschen von Terminen ist dem Büroteam vorbehalten.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->isOfficeStaff();
    }
}
