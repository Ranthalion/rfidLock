<?php

namespace App\Listeners;

use App\Events\CheckResourceHealth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Services\ResourceManager;

use App\Events\UpdateResourceAccessList;

use App\Models\Resource;

class CheckResourceHealthListener
{
    /**
     * Handle the event.
     *
     * @param  CheckResourceHealth  $event
     * @return void
     */
    public function handle(CheckResourceHealth $event)
    {
        $accessList = \DB::table('member_table')
                     ->select('hash')
                     ->where('resource_id', '=', $event->resource_id)
                     ->get();

        $resource = Resource::find($event->resource_id);

        // get a hash of the accessList for this resource
        $currentTags = array_map(function($n){
            return $n->hash;
        }, $accessList->all());
        // must be sorted
        sort($currentTags);
        // using md5 for now with a hyphen as a delimeter
        $currentHash = md5(join('-',$currentTags));

        // make request to resource to see if it's up to date and online
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->get($resource->network_address . '/health?hash='.$currentHash, ['connect_timeout' => 3.14]);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return '❌ not able to connect to resource';
        }

        if ($res->getStatusCode() != 200)
        {
            return '❌ unable to get device health status';
        }

        $healthCheck = json_decode($res->getBody(), true);

        if(isset($healthCheck['status'])) {
            $updateAttempt = event(new UpdateResourceAccessList($resource->id))[0];
            if($updateAttempt == $currentHash) {
                return '✅ good!';
            }
            return $updateAttempt;
        }

        if($healthCheck['success'] == 'ok') {
            return '✅ good!';
        }
    }
}
