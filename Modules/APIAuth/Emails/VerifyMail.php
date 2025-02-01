<?php

namespace Modules\APIAuth\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;
    public $token;
    public $tries = 5;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $token)
    {
        $this->url = $url;
        $this->token = $token;
    }

    /*
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('verifyEmail')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Email Verification');
    }
}
