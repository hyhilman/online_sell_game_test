<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use App\Topup;

class TopupPolicy
{
    use HandlesAuthorization;

    // public function before($user, $ability)
    // {
    //     if ($user->isAdmin()) {
    //         return true;
    //     }
    // }

    public function view(User $user, Topup $topup)
    {
        return $user->id == $topup->user_id;
    }

    public function store(User $user, Topup $topup)
    {
        return $user->id == $topup->user_id;
    }
}
