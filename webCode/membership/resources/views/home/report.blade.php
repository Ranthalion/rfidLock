@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
        	<div class="panel panel-default">
        		<div class="panel-heading">Members</div>
        		<div class="panel-body">

				        <table class="table">
				        	<thead>
				        		<tr>
				        			<th>Name</th>
				        			<th>Email</th>
				        			<th>Payment Via</th>
				        			<th>Last Payment</th>
				        			<th>Amount</th>
				        			<th>Status</th>
				        		</tr>
				        	</thead>
				        	<tbody>
				            @foreach( $summaries as $member )
				            	<tr>
				            		<td>{{ $member->name }}</td>
				            		<td>{{ $member->email }}</td>
				            		<td>{{ $member->provider }}</td>
				            		<td>{{ $member->paymentDate }}</td>
				            		<td>{{ $member->amount }}</td>
				            		<td>{{ $member->status }}</td>
				                </tr>
				            @endforeach
				            </tbody>
				        </table>

				        <div>
				        	{{count($summaries)}} total members
				        </div>
				    
        		</div>
        	</div>
        </div>
    </div>
</div>
@endsection

@section('pagescript')
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script>
	    $(document).ready(function(){
	    	$('table').DataTable();
		});
    </script>
@endsection