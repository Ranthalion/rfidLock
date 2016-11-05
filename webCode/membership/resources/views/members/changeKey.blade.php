@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
        	<div class="panel panel-default">
        		<div class="panel-heading">Edit Member</div>
        		<div class="panel-body">

                    {{ Form::model($member, ['action' => ['MemberController@updateKey', $member->id], 'method' => 'POST', 'class' => 'form-horizontal']) }}
                        
                        <div class="form-group{{ $errors->has('rfid') ? ' has-error' : '' }}">
                            <label for="rfid" class="col-md-4 control-label">rfid</label>

                            <div class="col-md-6">
                                {{ Form::text('rfid', '', ['class' => 'form-control']) }}

                                @if ($errors->has('rfid'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('rfid') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

            			<div class="row">
            				<div class="col-md-12 text-right">
            					<a class="btn btn-default" href="{{ url('/members') }}">Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
            				</div>
            			</div>

                    {{ Form::close() }}
                    
        		</div>
        	</div>
        </div>
    </div>
</div>
@endsection
