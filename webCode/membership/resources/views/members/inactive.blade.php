@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
        	<div class="panel panel-default">
        		<div class="panel-heading">Members</div>
        		<div class="panel-body">
        			<div class="row">
        				<div class="col-md-6">
        					<a href="/members">View Active Members</a>
        				</div>
        			</div>

    			 	@if ( !$members->count() )
				        There are no inactive members
				    @else
				        <table class="table">
				        	<thead>
				        		<tr>
				        			<th>Member</th>
				        			<th>Email</th>
				        			<th>Tier</th>
				        			<th>Status</th>
				        			<th>Payment Provider</th>
				        			<th>Actions</th>
				        		</tr>
				        	</thead>
				        	<tbody>
				            @foreach( $members as $member )
				            	<tr>
				            		<td>{{ $member->name }}</td>
				            		<td>{{ $member->email }}</td>
				            		<td>{{ $member->memberTier->description }}</td>
				            		<td>{{ $member->memberStatus->description }}</td>
				            		<td>{{ $member->paymentProvider->description }}</td>
				            		<td>
            			                <a class="btn btn-xs btn-info" href="{{ URL::to('members/' . $member->id . '/edit') }}">Edit</a>
            			                {!! Form::open(['method' => 'POST','route' => ['members.restore', $member->id], 'style' => 'display: inline;	']) !!}
            								{!! Form::submit('Restore', ['class' => 'btn btn-xs btn-success']) !!}
        								{!! Form::close() !!}
				            		</td>
				                </tr>
				            @endforeach
				            </tbody>
				        </table>

				        <div>
				        	{{count($members)}} total inactive members
				        </div>
				    @endif
        		</div>
        	</div>
        </div>
    </div>
</div>
@endsection