@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
        	<div class="panel panel-default">
        		<div class="panel-heading">Resources</div>
        		<div class="panel-body">
        			<div class="row">
        				<div class="col-md-12">
        					<a class="btn btn-primary pull-right" href="{{ URL::to('resources/create') }}">Add Resource</a>
        				</div>
        			</div>

    			 	@if ( !$resources->count() )
				        There are no resources
				    @else
				        <table class="table">
				        	<thead>
				        		<tr>
				        			<th>Resource</th>
				        			<th>Actions</th>
				        		</tr>
				        	</thead>
				        	<tbody>
				            @foreach( $resources as $resource )
				            	<tr>
				            		<td>{{ $resource->description }}</td>
				            		<td>
            			                <a class="btn btn-xs btn-info" href="{{ URL::to('resources/' . $resource->id . '/edit') }}">Edit</a>
            			                {!! Form::open(['method' => 'DELETE','route' => ['resources.destroy', $resource->id], 'style' => 'display: inline;	']) !!}
            								{!! Form::submit('Disable', ['class' => 'btn btn-xs btn-warning disable-resource']) !!}
        								{!! Form::close() !!}
				            		</td>
				                </tr>
				            @endforeach
				            </tbody>
				        </table>
				    @endif
        		</div>
        	</div>
        </div>
    </div>
</div>
@endsection


@section('pagescript')
    <script src="/js/resource.js"></script>
@endsection