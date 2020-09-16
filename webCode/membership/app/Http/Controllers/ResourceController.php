<?php

namespace App\Http\Controllers;

use App\Events\CheckResourceHealth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Resource;
use App\Http\Requests\StoreResource;
use Session;

class ResourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = Resource::orderBy('description')->get();

        array_map(function($resource){
            $health_status = "N/a";
            if($resource->network_address)
            {
                $health_status = event(new CheckResourceHealth($resource->id));
            }
            $resource->health_status = $health_status;
            return $resource;
        }, $resources->all());

        return view('resources.index', compact('resources'));
    }


    /**
     * Show the form for creating a new resource.
     *S
     * @return \Illuminate\Http\Response
     */
    public function create(Resource $resource)
    {
        
        return view('resources.create', compact('resource'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResource $request)
    {
        $input = $request->all();
        
        $resource = new Resource;
        $resource->fill($input);
        
        $resource->save();

        // redirect
        Session::flash('message', 'Successfully saved resource!');
        
        return redirect('resources');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $resource = Resource::find($id);
        
        return view('resources.edit', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreResource $request, $id)
    {
        $input = $request->all();
        
        $resource = Resource::find($id);
        
        $resource->fill($input);
        
        $resource->save();

        // redirect
        Session::flash('message', 'Successfully saved resource!');
        return redirect('resources');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resource = Resource::find($id);
        
        $resource->save();

        return redirect('resources');
    }

}
