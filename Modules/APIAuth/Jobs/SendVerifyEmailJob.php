<?php

namespace Modules\APIAuth\Jobs;

use Illuminate\Bus\Queueable;
use Modules\APIAuth\Entities\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Modules\APIAuth\Emails\VerifyMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendVerifyEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email, $verify_token;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $verify_token)
    {
        $this->email = $email;
        $this->verify_token = $verify_token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = url('api/verify-email') . '/' . $this->email . '/' . $this->verify_token;

        Mail::to($this->email)->send(new VerifyMail($url, $this->verify_token));
    }
}
