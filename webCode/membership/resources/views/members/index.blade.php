@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
        	<div class="panel panel-default">
        		<div class="panel-heading">Members</div>
        		<div class="panel-body">
        			<div class="row">
        				<div class="col-md-12">
        					<a class="btn btn-primary pull-right" href="{{ url('/addMember') }}">Add Member</a>
        				</div>
        			</div>

    			 	@if ( !$members->count() )
				        There are no members
				    @else
				        <ul>
				            @foreach( $members as $member )
				                <li>{{ $member->name }}</li>
				            @endforeach
				        </ul>
				    @endif
        		</div>
        	</div>
        </div>
    </div>
</div>
@endsection
