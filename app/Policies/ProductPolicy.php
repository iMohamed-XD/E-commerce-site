<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine whether the user can manage the product.
     */
    public function manage(User $user, Product $product): bool
    {
        return $user->shop && $product->shop_id === $user->shop->id;
    }
}
