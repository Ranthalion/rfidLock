<?php

namespace App\Listeners;

use App\Events\UpdateAllResources;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Resource;
use App\Events\UpdateResourceAccessList;

class UpdateAllResourcesListener
{
    /**
     * Handle the event.
     *
     * @param  UpdateAllResources  $event
     * @return void
     */
    public function handle(UpdateAllResources $event)
    {
        // dd("update all resources");
        $resources = Resource::all();

        foreach($resources as $resource) {
            if($resource->network_address) {
                event(new UpdateResourceAccessList($resource->id));
            }
        }

    }
}
