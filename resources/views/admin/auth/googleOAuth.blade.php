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
</span> {{ trans('messages.setting.google_oauth') }}</span>
        </h1>				
    </div>

@endsection

@section('content')
    
    <form action="{{ action('Admin\AuthController@googleOAuth') }}" method="POST" class="oauth-form">
        {{ csrf_field() }}
        
        <div class="tabbable">
            <div class="row">
                <div class="col-md-6">
                    <div>
                        @include('helpers.form_control', [
                            'type' => 'checkbox2',
                            'name' => 'google_enabled',
                            'value' => request()->google_enabled ? request()->google_enabled : Acelle\Model\Setting::get('oauth.google_enabled'),
                            'label' => trans('messages.oauth.google.enable.desc'),
                            'options' => ['no', 'yes'],
                        ])
                    </div>

                    <div class="oauth_settings" style="display:none">
                        <hr>

                        <p>
                            {!! trans('messages.google_oauth.guide') !!}
                        </p>

                        @include('helpers.form_control', [
                            'type' => 'text',
                            'name' => 'google_client_id',
                            'label' => trans('messages.oauth.google_client_id'),
                            'value' => request()->google_client_id ? request()->google_client_id : Acelle\Model\Setting::get('oauth.google_client_id'),
                        ])
                        @include('helpers.form_control', [
                            'type' => 'text',
                            'name' => 'google_client_secret',
                            'label' => trans('messages.oauth.google_client_secret'),
                            'value' => request()->google_client_secret ? request()->google_client_secret : Acelle\Model\Setting::get('oauth.google_client_secret'),
                        ])

                        <p>
                            {!! trans('messages.google_oauth.uri_guide') !!}
                        </p>
                        <h5 class="text-semibold">{{ trans('messages.oauth.origin_uri') }}</h5>
                        <p style="margin-bottom: 30px" class="d-flex align-items-center">
                                <code style="font-size: 18px" class="origin_uri">{{ url('') }}</code>
                                <button class="btn btn-light origin_uri-copy-button ml-4"><i class="material-symbols-rounded me-2">content_copy</i>{{ trans('messages.copy') }}</button>

                        <h5 class="text-semibold">{{ trans('messages.oauth.redirect_uri') }}</h5>
                        <p style="margin-bottom: 30px" class="d-flex align-items-center">
                                <code style="font-size: 18px" class="redirect_uri">{{ action('AuthController@googleCallback') }}</code>
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
        var GoogleOAuth = {
            check: function() {
                var checked = $('[name=google_enabled]:checked').length;

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

            GoogleOAuth.check();
            $('[name=google_enabled]').on('change', function(e) {
                e.preventDefault();

                GoogleOAuth.check();
            });
        });
    </script>
@endsection
