<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Page;
use App\Models\User;

class PagePolicy
{
    /**
     * Feste Schulseiten werden ausschließlich von Schulleitung und Administration gepflegt.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdminOrLeadership();
    }

    public function view(User $user, Page $page): bool
    {
        return $user->isAdminOrLeadership();
    }

    public function create(User $user): bool
    {
        return $user->isAdminOrLeadership();
    }

    public function update(User $user, Page $page): bool
    {
        return $user->isAdminOrLeadership();
    }

    public function delete(User $user, Page $page): bool
    {
        return $user->isAdminOrLeadership();
    }
}
