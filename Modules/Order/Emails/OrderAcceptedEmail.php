<?php

namespace Modules\Order\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderAcceptedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $username, $outlet_name, $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($username, $outlet_name, $order)
    {
        $this->username = $username;
        $this->outlet_name = $outlet_name;
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('order::order_accepted_email')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(__('success_action', ['model' => __('order'), 'action' => __('placed')]));
    }
}
