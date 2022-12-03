@extends('layouts.core.frontend')

@section('content')@section('content')
    @include('admin.notifications._top', ['notifications' => $notifications])
    

<script>
    $('div[data-type="admin-notification"]').unbind('click');

    $('div[data-type="admin-notification"]').click(function() {
        var confirmed = confirm('{{ trans('messages.admin.dashboard.notification_delete_confirm') }}');
        if (confirmed) {
            var element = $(this);
            element.fadeOut(4000);
            $.ajax("{{ url("notifications") }}/" + this.id, {
                method: 'DELETE',
                _method: 'DELETE', // See more at: method_field('DELETE')->toHtml()
                data: {_token: CSRF_TOKEN}
            }).done(function(data, textStatus, jqXHR) { // jqXHR.status == 200
                element.stop(); // stop fading, then fade again faster!
                element.fadeOut(200);
            }).fail(function(jqXHR, textStatus, errorThrown) { // jqXHR.status = 5xx hoac 4xx
                alert('Something went wrong, cannot delete notification');
                element.stop();
                element.show();
            });
        }
    });
</script>
@endsection
