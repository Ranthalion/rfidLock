@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
        	<div class="panel panel-default">
        		<div class="panel-heading">New Member</div>
        		<div class="panel-body">
                    {{ Form::model($member, ['action' => 'HomeController@checkForPayment', 'class' => 'form-horizontal']) }}
                        
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            {{ Form::label('email', 'Email', ['class' => 'col-md-4 control-label']) }}

                            <div class="col-md-6">
                                {{ Form::email('email', '', ['class' => 'form-control']) }}
                                
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('payment_provider_id') ? ' has-error' : '' }}">
                            {{ Form::label('payment_provider_id', 'Payment Via', ['class' => 'col-md-4 control-label']) }}

                            <div class="col-md-6">
                                {{ Form::select('payment_provider_id', $providers, 2, ['class' => 'form-control']) }}

                                @if ($errors->has('payment_provider_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('payment_provider_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
            			<div class="row">
            				<div class="col-md-12 text-right">
            					<a class="btn btn-default" href="{{ url('/') }}">Cancel</a>
                                <button type="submit" class="btn btn-primary">Next</button>
            				</div>
            			</div>

                    {{ Form::close() }}
                    
        		</div>
        	</div>
        </div>
    </div>
</div>
@endsection
