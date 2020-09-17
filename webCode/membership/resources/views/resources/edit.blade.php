@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
        	<div class="panel panel-default">
        		<div class="panel-heading">Edit Resource</div>
        		<div class="panel-body">

                    {{ Form::model($resource, ['action' => ['ResourceController@update', $resource->id], 'method' => 'PUT', 'class' => 'form-horizontal']) }}
                        
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            {{ Form::label('description', 'Description', ['class' => 'col-md-4 control-label']) }}

                            <div class="col-md-6">
                                {{ Form::text('description', null, ['class' => 'form-control']) }}

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('network_address') ? ' has-error' : '' }}">
                            {{ Form::label('network_address', 'URI', ['class' => 'col-md-4 control-label']) }}

                            <div class="col-md-6">
                                {{ Form::text('network_address', null, ['class' => 'form-control']) }}

                                @if ($errors->has('network_address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('network_address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('api_key') ? ' has-error' : '' }}">
                            {{ Form::label('api_key', 'API Key', ['class' => 'col-md-4 control-label']) }}

                            <div class="col-md-6">
                                {{ Form::text('api_key', null, ['class' => 'form-control']) }}

                                @if ($errors->has('api_key'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('api_key') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

            			<div class="row">
            				<div class="col-md-12 text-right">
            					<a class="btn btn-default" href="{{ url('/resources') }}">Cancel</a>
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
