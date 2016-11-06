<?php

namespace App\listeners;

use App\Events\MemberAdded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Services\MailChimp;
use App\Models\Member;

class MailSubscription implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MemberAdded  $event
     * @return void
     */
    public function handle(MemberAdded $event)
    {
        $svc = new MailChimp();
        $result = $svc->addSubscriber($event->member->email);
    }
}
