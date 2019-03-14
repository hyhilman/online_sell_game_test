<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use App\Order;

class OrderPolicy
{
    use HandlesAuthorization;

    // public function before($user, $ability)
    // {
    //     if ($user->isAdmin()) {
    //         return true;
    //     }
    // }

    public function view(User $user, Order $order)
    {
        return $user->id == $order->user_id;
    }

    public function store(User $user, Order $order)
    {
        return $user->id == $order->user_id;
    }
}
