<script>
    @foreach (['success'] as $msg)
        @if(Session::has('alert-' . $msg))
            $(document).ready(function() {
                notify({
                    type: "{{ $msg }}",
                    title: '{{ trans('messages.notify.' . $msg)}}',
                    message: '{!! preg_replace('/[\r\n]+/', ' ', Session::get('alert-' . $msg)) !!}',
                    dismissible: true,
                });
            });

        @endif
    @endforeach

    @if (request()->session()->get('user-activated'))
        $(document).ready(function() {
            notify({
                type: "success",
                message: `xxxx{!! request()->session()->get('user-activated') !!}`,
                dismissible: true,
            });
        });
        <?php request()->session()->forget('user-activated'); ?>
    @endif
</script>
