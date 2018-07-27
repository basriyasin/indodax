<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Indodax;
use Storage;

class PriceUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    
    public $lastPrice;
    public $orderHistory;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($time)
    {
        $indodax   = new Indodax();
        $lastPrice = Indodax::lastBtcIdrPrice()->ticker ?? null;
        if(!is_null($lastPrice)) {
            $this->lastPrice = [
                (int) $lastPrice->server_time *1000,
                (int) $lastPrice->last,
            ];
        }
        $this->orderHistory = Indodax::transhistory();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('public');
    }
}
