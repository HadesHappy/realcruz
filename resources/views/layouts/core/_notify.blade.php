<script>
    @if (null !== Session::get('orig_admin_id') && Auth::user()->admin)
        notify({
            type: 'warning',
            message: `{!! trans('messages.current_login_as', ["name" => Auth::user()->displayName()]) !!}<br>{!! trans('messages.click_to_return_to_origin_user', ["link" => action("Admin\AdminController@loginBack")]) !!}`,
            timeout: false,
        });
    @endif

    @if (null !== Session::get('orig_admin_id') && Auth::user()->admin)
        notify({
            type: 'warning',
            message: `{!! trans('messages.site_is_offline') !!}`,
            timeout: false,
        });
    @endif
</script>