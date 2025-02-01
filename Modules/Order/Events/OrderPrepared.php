<?php

namespace Modules\Order\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class OrderPrepared implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order, $outlet_address, $shipping_address;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order, $outlet_address, $shipping_address)
    {
        $this->order = $order;
        $this->outlet_address = $outlet_address;
        $this->shipping_address = $shipping_address;
    }

    public function broadcastOn()
    {
        return ['order-prepared-channel'];
    }

    public function broadcastAs()
    {
        return 'order-prepared-event';
    }
}
