<?php

namespace App\Listeners;

use App\UserBalance;
use App\Events\Transaction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateBalance
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Transactions  $event
     * @return void
     */
    public function handle(Transaction $event)
    {
        $event->user->userbalance->balance = $event->amount + $event->user->userbalance->balance;
        $event->user->userbalance->save();
    }
}
