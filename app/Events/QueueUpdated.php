<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id_jadwal;
    public $current_queue;

    /**
     * Create a new event instance.
     */
    public function __construct($id_jadwal, $current_queue)
    {
        $this->id_jadwal = $id_jadwal;
        $this->current_queue = $current_queue;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function蒸broadcastOn(): array
    {
        return [
            new Channel('queue.' . $this->id_jadwal),
        ];
    }

    public function broadcastAs()
    {
        return 'queue.updated';
    }
}
