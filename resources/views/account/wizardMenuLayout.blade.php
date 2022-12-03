@extends('layouts.popup.small')

@section('bar-title')
    {{ trans('messages.account.welcome_user', [
        'name' => Auth::user()->displayName(),
    ]) }}
@endsection

@section('content')
    <form id="wizardColorScheme" class="" action="{{ action('AccountController@wizardMenuLayout') }}"
        method="POST">
        {{ csrf_field() }}

        <h2 class="mt-0 text-center">{{ trans('messages.account.menu_layout') }}</h2>

        <div class="text-center my-4">
            <div>
                @include('layouts.core._menu_layout_switch', [
                    'menu_layout' => request()->user()->customer->menu_layout,
                ])
            </div>
        </div>

        
        <p class="text-center">
            {{ trans('messages.account.menu_layout.into') }}
        </p>
        <div class="mt-5 text-center">
            <button class="btn btn-primary px-4">{{ trans('messages.ok') }}</button>
        </div>
    </form>

    <script>
        $(function() {
            $('#wizardColorScheme').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    method: "POST",
                    url: $(this).attr('action'),
                    data: $(this).serialize()
                })
                .done(function( response ) {
                    window.location.reload();
                });
            });

            $('#wizardColorScheme [name=menu_layout]').on('change', function(e) {
                var type = $('#wizardColorScheme [name=menu_layout]:checked').val();

                $('body').removeClass('topbar');
                $('body').removeClass('leftbar');
                $('body').removeClass('leftbar-closed');

                $('body').addClass(type + 'bar');
            });
        });
    </script>
@endsection