<?php

namespace Modules\Order\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class OrderStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $status, $id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($status, $id)
    {
        $this->status = $status;
        $this->id = $id;
    }

    public function broadcastOn()
    {
        return ['order-status-channel'];
    }

    public function broadcastAs()
    {
        return 'order-status-changed';
    }
}
