<?php

namespace App\Listeners;

use App\Events\MemberRevoked;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Mail\RevokeMember;
use Illuminate\Support\Facades\Mail;


class NotifyRevokedMember
{
    /**
     * Handle the event.
     *
     * @param  MemberRevoked  $event
     * @return void
     */
    public function handle(MemberRevoked $event)
    {
        Mail::to($event->member->email)->bcc("Michael.Lane@hackrva.org")->send(new RevokeMember($event->member));
    }
}
