@extends('layouts.core.login')

@section('title', trans('messages.login'))

@section('content')
<style>
    .small {
        font-size: 0.86rem;
    }
</style>
<!-- Advanced login -->
<form class="" role="form" method="POST" action="{{ url('/login') }}">
    {{ csrf_field() }}

    <div class="panel panel-body p-4 rounded-3 bg-white shadow">

        <h4 class="text-semibold mt-0 mb-4 fw-600 fs-5">{{ trans('messages.login') }}</h4>

        <div class="form-group login-email-input has-feedback has-feedback-left{{ $errors->has('email') ? ' has-error' : '' }}">
            <input id="email" type="email" class="form-control" name="email" placeholder="{{ trans("messages.email") }}"
	 value="{{ old('email') ? old('email') : (isset(\Acelle\Model\User::getAuthenticateFromFile()['email']) ? \Acelle\Model\User::getAuthenticateFromFile()['email'] : "") }}"
            >
            <div class="form-control-feedback">
	<i class="icon-envelop5 text-muted"></i>
            </div>
            @if ($errors->has('email'))
	<span class="help-block">
	    <strong>{{ $errors->first('email') }}</strong>
	</span>
            @endif
        </div>

        <div class="form-group login-password-input has-feedback has-feedback-left{{ $errors->has('password') ? ' has-error' : '' }}">
            <input id="password" type="password" class="form-control" name="password" placeholder="{{ trans("messages.password") }}"
	value="{{ isset(\Acelle\Model\User::getAuthenticateFromFile()['password']) ? \Acelle\Model\User::getAuthenticateFromFile()['password'] : "" }}"
            >
            <div class="form-control-feedback">
	<i class="icon-lock2 text-muted"></i>
            </div>
            @if ($errors->has('password'))
	<span class="help-block">
	    <strong>{{ $errors->first('password') }}</strong>
	</span>
            @endif
        </div>

        <div class="form-group login-options mt-4">
            <div class="row align-items-center small">
                <div class="col-sm-6">
                    <label class="checkbox-inline d-flex align-items-center m-0">
                        <input type="checkbox" class="styled me-2" checked="checked" name="remember">
                        <span class="ms-2">{{ trans("messages.stay_logged_in") }}</span>
                    </label>
                </div>

                <div class="col-sm-6 text-end text-semibold fw-600">
                    <a href="{{ url('/password/reset') }}">{{ trans("messages.forgot_password") }}</a>
                </div>
            </div>
        </div>

        @if (\Acelle\Model\Setting::get('login_recaptcha') == 'yes')

            <!-- hCaptcha -->
            @if (\Acelle\Model\Setting::getCaptchaProvider() == 'hcaptcha')
                @php
                    $hcaptcha = \Acelle\Hcaptcha\Client::initialize();
                @endphp
                {!! $hcaptcha->renderFormHtml($errors) !!}
            @else
                {!! \Acelle\Library\Tool::showReCaptcha($errors) !!}
            @endif
        @endif

        <button type="submit" class="btn rounded-2 btn-primary d-block login-button py-2 fw-600"
            style="width:100%;text-transform:uppercase"
        ><span class="me-2">{{ trans("messages.login") }}</span> <span class="material-symbols-rounded">
            login
            </span></button>
            

            @if (
                \Acelle\Model\Setting::get('oauth.google_enabled') == 'yes' ||
                \Acelle\Model\Setting::get('oauth.facebook_enabled') == 'yes'
            )
                <div class="socialite_buttons">
                    @if (\Acelle\Model\Setting::get('oauth.google_enabled') == 'yes')
                        <div class="text-center mt-2">
                            <a href="{{ action('AuthController@googleRedirect', [
                                'language_code' => language_code(),
                            ]) }}" class="btn btn-light d-flex align-items-center rounded-3 py-2" style="width:100%">
                                <img width="25px" src="{{ url('images/google-login.svg') }}" />
                                <span class="ms-3 display-6 text-center" style="font-size:15px;width:80%">{{ trans('messages.continue_with_google') }}</span>
                            </a>
                        </div>
                    @endif

                    @if (\Acelle\Model\Setting::get('oauth.facebook_enabled') == 'yes')
                        <div class="text-center mt-2">
                            <a href="{{ action('AuthController@facebookRedirect') }}" class="btn btn-light d-flex align-items-center rounded-3 py-2" style="width:100%">
                                <img width="25px" src="{{ url('images/icons/facebook-logo.svg') }}" />
                                <span class="ms-3 display-6 text-center" style="font-size:15px;width:80%">{{ trans('messages.continue_with_facebook') }}</span>
                            </a>
                        </div>
                    @endif
                </div>
            @endif
    </div>

    @if (\Acelle\Model\Setting::get('enable_user_registration') == 'yes')
        <div class="text-center mt-4 text-white small">
            {!! trans('messages.need_a_account_create_an_one', [
	'link' => action('UserController@register')
            ]) !!}
        </div>
    @endif

    
</form>
<!-- /advanced login -->

<script>
    function addButtonLoadingEffect(button) {
        button.addClass('button-loading');
        button.append('<div class="loader"></div>');
    }

    function removeButtonLoadingEffect(button) {
        button.removeClass('button-loading');
        button.find('.loader').remove();
    }
    $('.login-button').on('click', function(e) {
        e.preventDefault();

        $(this).html('{{ trans('messages.login.please_wait') }}');

        $(this).closest('form').addClass('loading');

        addButtonLoadingEffect($(this));

        $(this).closest('form').submit();
    });
</script>

@endsection
