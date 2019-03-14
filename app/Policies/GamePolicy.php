<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use App\Game;

class GamePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if( $user->isAdmin() ) {
            return true;
        }
    }

    public function view(User $user, Game $game)
    {
        return true;
    }

    public function store(User $user, Game $game)
    {
        return false;
    }

    public function update(User $user, Game $game)
    {
        return false;
    }

    public function destroy(User $user, Game $game)
    {
        return false;
    }
}
