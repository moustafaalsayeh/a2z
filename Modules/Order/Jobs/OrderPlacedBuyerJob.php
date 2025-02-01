<?php

namespace Modules\Order\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Order\Emails\OrderPlacedBuyerEmail;

class OrderPlacedBuyerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email, $username, $order_created_at;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $username, $order_created_at)
    {
        $this->email = $email;
        $this->username = $username;
        $this->order_created_at = $order_created_at;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new OrderPlacedBuyerEmail($this->username, $this->email, $this->order_created_at));
    }
}
