@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
        	<div class="panel panel-default">
        		<div class="panel-heading">Add Resource</div>
        		<div class="panel-body">
                    {{ Form::model($resource, ['action' => 'ResourceController@store', 'class' => 'form-horizontal']) }}
                        
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            {{ Form::label('description', 'Description', ['class' => 'col-md-4 control-label']) }}

                            <div class="col-md-6">
                                {{ Form::text('description', '', ['class' => 'form-control']) }}

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
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
