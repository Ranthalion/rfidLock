<?php

namespace App\Listeners;

use App\Events\MemberApproachingRevokation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\PendingRevokation;

class NotifyPendingRevokation
{
    /**
     * Handle the event.
     *
     * @param  MemberApproachingRevokation  $event
     * @return void
     */
    public function handle(MemberApproachingRevokation $event)
    {
        Mail::to($event->member->email)->bcc("Michael.Lane@hackrva.org")->send(new PendingRevokation($event->member));
    }
}
