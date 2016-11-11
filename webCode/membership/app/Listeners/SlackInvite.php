<?php

namespace App\listeners;

use App\Events\MemberAdded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Member;
use App\Services\SlackInviter;

class SlackInvite implements ShouldQueue
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
        $slack = new SlackInviter();
        $slack->sendInvite($event->member->email, $event->member->name);
    }
}
