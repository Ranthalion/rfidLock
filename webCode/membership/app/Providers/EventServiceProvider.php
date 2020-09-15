<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\MemberAdded' => [
            'App\Listeners\WelcomeMailer',
            'App\Listeners\SlackInvite',
            'App\Listeners\MailSubscription',
        ],
        'App\Events\MemberApproachingRevokation' => [
            'App\Listeners\NotifyPendingREvokation'
        ],
        'App\Events\MemberRevoked' => [
            'App\Listeners\NotifyRevokedMember'
        ],
        'App\Events\MemberReinstated' => [
            'App\Listeners\ReinstateMember'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
