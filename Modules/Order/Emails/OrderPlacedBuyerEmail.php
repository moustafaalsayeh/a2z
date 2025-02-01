<?php

namespace Modules\Order\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPlacedBuyerEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $username, $email, $created_at;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($username, $email, $created_at)
    {
        $this->username = $username;
        $this->email = $email;
        $this->created_at = $created_at;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('order::order_placed_buyer_email')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(__('success_action', ['model' => __('order'), 'action' => __('placed')]));
    }
}
