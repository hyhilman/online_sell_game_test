<?php

namespace App\Providers;

use App\Game;
use App\Policies\GamePolicy;

use App\Order;
use App\Policies\OrderPolicy;

use App\Topup;
use App\Policies\TopupPolicy;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Game::class => GamePolicy::class,
        Order::class => OrderPolicy::class,
        Topup::class => TopupPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //
    }
}
