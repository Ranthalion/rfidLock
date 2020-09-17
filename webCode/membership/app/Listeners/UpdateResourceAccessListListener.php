<?php

namespace App\Listeners;

use App\Events\UpdateResourceAccessList;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Resource;
use App\Models\Member;

class UpdateResourceAccessListListener
{
    /**
     * Handle the event.
     *
     * @param  UpdateResourceAccessList  $event
     * @return void
     */
    public function handle(UpdateResourceAccessList $event)
    {

        // dd($event->resource_id);
        $resource = Resource::find($event->resource_id);
        $accessList = $accessList = \DB::table('member_table')
            ->select('*')
            ->where('resource_id', '=', $event->resource_id)
            ->get();

        $currentTags = array_map(function($n){
            return $n->hash;
        }, $accessList->all());

        sort($currentTags);
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->post($resource->network_address . '/update?key=' . $resource->api_key, [
                \GuzzleHttp\RequestOptions::JSON => (object) ['rfids'=> $currentTags]
                ]);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return "❌ unauthorized - update the apikey";
        }

        if($res->getStatusCode() != 200) {
            return "❌ not able to update the device's access list";
        }

        return json_decode($res->getBody(), true)['current'];
    }
}
