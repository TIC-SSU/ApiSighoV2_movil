<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VerificarCachePoblacionAsegurada
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $cacheKey1;
    public $cacheKey2;
    public $cacheKey3;
    public $cacheKey4;
    /**
     * Create a new event instance.
     */
    public function __construct($cacheKey1, $cacheKey2, $cacheKey3, $cacheKey4)
    {
        $this->cacheKey1 = $cacheKey1;
        $this->cacheKey2 = $cacheKey2;
        $this->cacheKey3 = $cacheKey3;
        $this->cacheKey4 = $cacheKey4;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
