@extends('layouts.core.backend')

@section('title', trans('messages.settings'))

@section('page_header')

    <div class="page-title">				
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action('Admin\AuthController@index') }}">{{ trans('messages.oauth') }}</a></li>
        </ul>
        <h1>
            <span class="text-gear"><span class="material-symbols-rounded">
                admin_panel_settings
</span> {{ trans('messages.setting.facebook_oauth') }}</span>
        </h1>				
    </div>

@endsection

@section('content')
    
    <form action="{{ action('Admin\AuthController@facebookOAuth') }}" method="POST" class="oauth-form">
        {{ csrf_field() }}
        
        <div class="tabbable">
            <div class="row">
                <div class="col-md-6">
                    <div>
                        @include('helpers.form_control', [
                            'type' => 'checkbox2',
                            'name' => 'facebook_enabled',
                            'value' => request()->facebook_enabled ? request()->facebook_enabled : Acelle\Model\Setting::get('oauth.facebook_enabled'),
                            'label' => trans('messages.oauth.facebook.enable.desc'),
                            'options' => ['no', 'yes'],
                        ])
                    </div>

                    <div class="oauth_settings" style="display:none">
                        <hr>

                        <p>
                            {!! trans('messages.facebook_oauth.guide') !!}
                        </p>

                        @include('helpers.form_control', [
                            'type' => 'text',
                            'name' => 'facebook_client_id',
                            'label' => trans('messages.oauth.facebook_client_id'),
                            'value' => request()->facebook_client_id ? request()->facebook_client_id : Acelle\Model\Setting::get('oauth.facebook_client_id'),
                        ])
                        @include('helpers.form_control', [
                            'type' => 'text',
                            'name' => 'facebook_client_secret',
                            'label' => trans('messages.oauth.facebook_client_secret'),
                            'value' => request()->facebook_client_secret ? request()->facebook_client_secret : Acelle\Model\Setting::get('oauth.facebook_client_secret'),
                        ])

                        <p>
                            {!! trans('messages.facebook_oauth.uri_guide') !!}
                        </p>
                        <h5 class="text-semibold">{{ trans('messages.oauth.origin_uri') }}</h5>
                        <p style="margin-bottom: 30px" class="d-flex align-items-center">
                                <code style="font-size: 18px" class="origin_uri">{{ url('') }}</code>
                                <button class="btn btn-light origin_uri-copy-button ml-4"><i class="material-symbols-rounded me-2">content_copy</i>{{ trans('messages.copy') }}</button>

                        <h5 class="text-semibold">{{ trans('messages.oauth.redirect_uri') }}</h5>
                        <p style="margin-bottom: 30px" class="d-flex align-items-center">
                                <code style="font-size: 18px" class="redirect_uri">{{ action('AuthController@facebookCallback') }}</code>
                                <button class="btn btn-light redirect_uri-copy-button ml-4"><i class="material-symbols-rounded me-2">content_copy</i>{{ trans('messages.copy') }}</button>
                        </p>
                    </div>
                </div>
            </div>
           
        </div>

        <div class="text-left">
            <button class="btn btn-secondary mr-1"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
            <a href="{{ action('Admin\AuthController@index') }}" class="btn btn-light">{{ trans('messages.cancel') }}</a>
        </div>
    </form>
        
    <script>
        var facebookOAuth = {
            check: function() {
                var checked = $('[name=facebook_enabled]:checked').length;

                if (checked) {
                    $('.oauth_settings').show();
                } else {
                    $('.oauth_settings').hide();
                }
            }
        }

        $(function() {
            $('.origin_uri-copy-button').on('click', function(e) {
                e.preventDefault();

                var code = $('.origin_uri').html();

                copyToClipboard(code);

                notify('success', '{{ trans('messages.notify.success') }}', '{{ trans('messages.oauth.origin_uri.copied') }}');
            });

            $('.redirect_uri-copy-button').on('click', function(e) {
                e.preventDefault();

                var code = $('.redirect_uri').html();

                copyToClipboard(code);

                notify('success', '{{ trans('messages.notify.success') }}', '{{ trans('messages.oauth.redirect_uri.copied') }}');
            });

            facebookOAuth.check();
            $('[name=facebook_enabled]').on('change', function(e) {
                e.preventDefault();

                facebookOAuth.check();
            });
        });
    </script>
@endsection
