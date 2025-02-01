<?php

namespace Modules\Order\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Order\Emails\OrderAcceptedEmail;
use Modules\Order\Entities\Order;

class OrderAcceptedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email, $outlet, $username, $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $username, $outlet, Order $order)
    {
        $this->email = $email;
        $this->outlet = $outlet;
        $this->username = $username;
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new OrderAcceptedEmail($this->username, $this->outlet, $this->order));
    }
}
