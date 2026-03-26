<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can manage the order.
     */
    public function manage(User $user, Order $order): bool
    {
        return $user->shop && $order->shop_id === $user->shop->id;
    }
}
