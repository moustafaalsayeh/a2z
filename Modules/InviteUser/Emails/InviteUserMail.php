<?php

namespace Modules\InviteUser\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;
    public $tries = 5;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /*
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->markdown('verifyEmailNew')
        //     ->from(config('mail.from.address'), config('mail.from.name'))
        //     ->subject('Email Verification');
    }
}
