@extends('layouts.core.login')

@section('title', trans('messages.password_reset'))

@section('content')
    
    <!-- send reset password email -->
    <form class="" role="form" method="POST" action="{{ url('/password/email') }}">
        {{ csrf_field() }}
        
        <div class="panel panel-body p-4 rounded-3 bg-white shadow">                        
            
            @if (session('status'))
                <div class="alert alert-success">
        {{ session('status') }}
                </div>
            @endif
            
            <h4 class="text-semibold mt-0">{{ trans('messages.password_reset') }}</h4>
            <p>{{ trans('messages.password_reset.help') }}</p>
            
            <div class="form-group has-feedback has-feedback-left{{ $errors->has('email') ? ' has-error' : '' }}">
                <label>{{ trans('messages.enter_your_registered_email_here') }}</label>
                <input id="email" type="email" class="form-control" name="email" placeholder="{{ trans("messages.email") }}" value="{{ old('email') }}">
                <div class="form-control-feedback has-label">
        <i class="icon-envelop5 text-muted"></i>
                </div>
                @if ($errors->has('email'))
        <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
                @endif                            
            </div>
            
            <button type="submit" class="btn btn-secondary">
                {{ trans('messages.send_password_reset_link') }}  <i class="icon-circle-right2 position-right"></i>
            </button>
            <a href="{{ url("/login") }}" class="btn btn-light">
                {{ trans("messages.return_to_login") }}
            </a>
            
        </div>
    </form>
    <!-- /send reset password email -->                
    
@endsection



