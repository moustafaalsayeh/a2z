<?php

namespace Modules\Order\Jobs;

use Illuminate\Bus\Queueable;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Order\Emails\OrderPlacedOutletEmail;

class OrderPlacedOutletJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order, $email;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, Order $order)
    {
        $this->email = $email;
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new OrderPlacedOutletEmail($this->order));
    }
}
