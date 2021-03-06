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
        'App\Events\CheckResourceHealth' => [
            'App\Listeners\CheckResourceHealthListener'
        ],
        'App\Events\UpdateResourceAccessList' => [
            'App\Listeners\UpdateResourceAccessListListener'
        ],
        'App\Events\UpdateAllResources' => [
            'App\Listeners\UpdateAllResourcesListener'
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
