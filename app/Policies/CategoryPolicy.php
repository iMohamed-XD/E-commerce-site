<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Determine whether the user can manage the category.
     */
    public function manage(User $user, Category $category): bool
    {
        return $user->shop && $category->shop_id === $user->shop->id;
    }
}
