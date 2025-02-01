<?php

namespace Modules\APIAuth\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $first_name;
    public $token;
    public $url;
    public $tries = 5;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->email = $data['email'];
        $this->first_name = $data['first_name'];
        $this->token = $data['token'];
        $this->url = config('app.url') . "/resetpassword?token=$this->token";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('resetPasswordEmail')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(__('Reset your account password'));
    }
}
