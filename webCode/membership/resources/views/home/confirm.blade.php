@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
        	<div class="panel panel-default">
        		<div class="panel-heading">Confirm Member</div>
        		<div class="panel-body">
                    {{ Form::model($member, ['action' => 'HomeController@storeMember', 'class' => 'form-horizontal']) }}
                        
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            {{ Form::label('name', 'Name', ['class' => 'col-md-4 control-label']) }}

                            <div class="col-md-6">
                                {{ Form::text('name', null, ['class' => 'form-control', 'readonly'=>'readonly']) }}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            {{ Form::label('email', 'Email', ['class' => 'col-md-4 control-label']) }}

                            <div class="col-md-6">
                                {{ Form::email('email', null, ['class' => 'form-control', 'readonly'=>'readonly']) }}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('payment_provider_id') ? ' has-error' : '' }}">
                            {{ Form::label('payment_provider_id', 'Payment Via', ['class' => 'col-md-4 control-label']) }}

                            <div class="col-md-6">
                                <input type="text" class="form-control" readonly="readonly" value="{{$provider->description}}">
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('member_tier_id') ? ' has-error' : '' }}">
                            {{ Form::label('member_tier_id', 'Membership Tier', ['class' => 'col-md-4 control-label']) }}
                                                        
                            <div class="col-md-6">
                                <input type="text" class="form-control" readonly="readonly" value="{{$tier->description}}">
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('rfid') ? ' has-error' : '' }}">
                            <label for="rfid" class="col-md-4 control-label">rfid</label>

                            <div class="col-md-6">
                                {{ Form::text('rfid', null, ['class' => 'form-control', 'readonly'=>'readonly']) }}
                            </div>
                        </div>
                        
            			<div class="row">
            				<div class="col-md-12 text-right">
            					<a class="btn btn-default cancel-confirmation" href="{{ url('/') }}">Cancel</a>
                                <button type="submit" class="btn btn-primary">Confirm</button>
            				</div>
            			</div>

                    {{ Form::close() }}
                    
        		</div>
        	</div>
        </div>
    </div>
</div>
@endsection

@section('pagescript')
    <script src="/js/confirm-member.js"></script>
@endsection