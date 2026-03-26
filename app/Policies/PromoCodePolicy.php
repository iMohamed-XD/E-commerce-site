<?php

namespace App\Policies;

use App\Models\PromoCode;
use App\Models\User;

class PromoCodePolicy
{
    /**
     * Determine whether the user can manage the promo code.
     */
    public function manage(User $user, PromoCode $promoCode): bool
    {
        return $user->shop && $promoCode->shop_id === $user->shop->id;
    }
}
