<?php

namespace App\Listeners;

use App\Events\MemberReinstated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\RenewMembership;

class ReinstateMember
{
    /**
     * Handle the event.
     *
     * @param  MemberReinstated  $event
     * @return void
     */
    public function handle(MemberReinstated $event)
    {
        Mail::to($event->member->email)->bcc("Michael.Lane@hackrva.org")->send(new RenewMembership($event->member));
    }
}
