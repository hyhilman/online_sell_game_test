<?php

namespace App\Listeners;

use App\Order;
use App\Events\Transaction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderLogger
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
        if($event->data instanceof Order) {
            $event->user->orders()->save($event->data);
        }
    }
}
