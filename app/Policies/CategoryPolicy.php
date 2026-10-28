<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Kategorien werden vom Büroteam verwaltet.
     */
    public function viewAny(User $user): bool
    {
        return $user->isOfficeStaff();
    }

    public function view(User $user, Category $category): bool
    {
        return $user->isOfficeStaff();
    }

    public function create(User $user): bool
    {
        return $user->isOfficeStaff();
    }

    public function update(User $user, Category $category): bool
    {
        return $user->isOfficeStaff();
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->isOfficeStaff();
    }
}
