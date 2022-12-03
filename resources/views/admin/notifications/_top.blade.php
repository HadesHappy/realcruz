@if (count($notifications))
    <div class="mt-4">
        @foreach ($notifications as $n)
            @include('elements._notification', [
                'level' => $n->level,
                'title' => $n->title,
                'message' => strip_tags($n->message),
                'debug' => $n->debug,
                'params' => ['id' => $n->uid, 'data-type' => 'admin-notification']
            ])
        @endforeach
    </div>
@endif

<script>
    $('div[data-type="admin-notification"]').click(function() {
        var confirmed = confirm('{{ trans('messages.admin.dashboard.notification_delete_confirm') }}');
        if (confirmed) {
            var element = $(this);
            element.fadeOut(4000);
            $.ajax("{{ url("notifications") }}/" + this.id + "/hide", {
                method: 'POST',
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