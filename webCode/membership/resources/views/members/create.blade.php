@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
        	<div class="panel panel-default">
        		<div class="panel-heading">Add Member</div>
        		<div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/addMember') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('payment_provider') ? ' has-error' : '' }}">
                            <label for="payment_provider" class="col-md-4 control-label">Payment Via</label>

                            <div class="col-md-6">
                                <!-- TODO: [ML] Change to drop down list and pre-populate to Paypal -->
                                <input id="payment_provider" type="text" class="form-control" name="payment_provider" value="{{ old('payment_provider') }}" required autofocus>

                                @if ($errors->has('payment_provider'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('payment_provider') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('member_tier') ? ' has-error' : '' }}">
                            <label for="member_tier" class="col-md-4 control-label">Membership Tier</label>

                            <div class="col-md-6">
                                <!-- TODO: [ML] Change to drop down list and pre-populate to Standard -->
                                <input id="member_tier" type="text" class="form-control" name="member_tier" value="{{ old('member_tier') }}" required autofocus>

                                @if ($errors->has('member_tier'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('member_tier') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
            			<div class="row">
            				<div class="col-md-12 text-right">
            					<a class="btn btn-default" href="{{ url('/members/index') }}">Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
            				</div>
            			</div>

                    </form>
        		</div>
        	</div>
        </div>
    </div>
</div>
@endsection
