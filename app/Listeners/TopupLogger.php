<?php

namespace App\Listeners;

use App\Topup;
use App\Events\Transaction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TopupLogger
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
     * @param  Transaction $event
     * @return void
     */
    public function handle(Transaction $event)
    {
        if($event->data instanceof Topup) {
            $event->user->orders()->save($event->data);
        }
    }
}
