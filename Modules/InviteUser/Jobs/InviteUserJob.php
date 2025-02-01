<?php

namespace Modules\InviteUser\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\SendInvitationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\InviteUser\Entities\Invitation;
use Modules\InviteUser\Emails\InviteUserMail;

class InviteUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_invitation;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Invitation $user_invitation)
    {
        $this->user_invitation = $user_invitation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = config('app.url') . url('accept-invitation') . '/' . $this->user_invitation->token;

        Mail::to($this->user_invitation->email)->send(new SendInvitationMail($url));
    }
}
