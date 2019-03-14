<?php

namespace App\Events;

use App\Topup;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Transaction extends Event
{
    use SerializesModels;
    public $user;
    public $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $data)
    {
        $this->user = $user;
        $this->data = $data;

        if($this->data instanceof Topup) {
            $this->amount = $this->data->amount;
        } else {
            $this->amount = $this->data->game->price * -1;
        }
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
