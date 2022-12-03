@extends('layouts.popup.small')

@section('bar-title')
    {{ trans('messages.account.welcome_user', [
        'name' => Auth::user()->displayName(),
    ]) }}
@endsection

@section('content')
    <form id="wizardColorScheme" class="" action="{{ action('AccountController@wizardColorScheme') }}"
        method="POST">
        {{ csrf_field() }}
        <div class="text-center my-4">
            <img width="100px" src="{{ url('images/pantone.svg') }}" />
        </div>
        <h2 class="mt-0 text-center">{{ trans('messages.account.color_scheme') }}</h2>
        <p class="text-center">
            {{ trans('messages.account.color_scheme.into') }}
        </p>

        <div class="row">
            <div class="col-md-12" style="text-align: center">
                <label class="mb-3  small text-muted">{{ trans('messages.theme_mode') }}:</label>
                <div class="mb-4">
                    @include('layouts.core._theme_mode_control', [
                        'theme_mode' => Auth::user()->customer->theme_mode,
                    ])
                </div>
            </div>
        </div>

        <div class="text-center">
            <label class="mb-4 d-block small text-muted">{{ trans('messages.color_scheme') }}:</label>

            @include('layouts.core._theme_color_control', [
                'theme_color' => Auth::user()->customer->getColorScheme(),
            ])  
            
        </div>
        <div class="mt-5 text-center">
            <button class="btn btn-primary px-4">{{ trans('messages.next') }}</button>
        </div>
    </form>

    <script>
        $(function() {
            $('.color-scheme-select').on('click', function(e) {
                var value = $(this).val();
                $("body").removeClass (function (index, className) {
                    return (className.match (/(^|\s)theme-\S+/g) || []).join(' ');
                });
                $('body').addClass('theme-' + value);
            });
            
            $('#wizardColorScheme').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    method: "POST",
                    url: $(this).attr('action'),
                    data: $(this).serialize()
                })
                .done(function( response ) {
                    wizardUserPopup.loadHtml(response);
                });
            });
        });
    </script>
@endsection