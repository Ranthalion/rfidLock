<?php

namespace App\Listeners;

use App\Events\MemberAdded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Member;
use App\Mail\Welcome;
use Illuminate\Support\Facades\Mail;

class WelcomeMailer implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  MemberAdded  $event
     * @return void
     */
    public function handle(MemberAdded $event)
    {
        Mail::to($event->member->email)->bcc("Michael.Lane@hackrva.org")->send(new Welcome($event->member));
    }
}
