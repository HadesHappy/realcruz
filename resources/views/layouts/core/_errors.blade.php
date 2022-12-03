@if (count($errors) > 0)
    <!-- Form Error List -->
    <div class="alert alert-danger alert-noborder alert-dismissible">
        <strong>{{ trans('messages.check_entry_try_again') }}</strong>

        <br><br>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@foreach (['danger', 'warning', 'info', 'error'] as $msg)
    @php
        $class = $msg;
        if ($msg == 'error') {
            $class = 'danger';
        }
    @endphp
    @if(Session::has('alert-' . $msg))
        <!-- Form Error List -->
        <div class="alert alert-{{ $class }} alert-noborder alert-dismissible">
            <strong>{{ trans('messages.' . $msg) }}</strong>

            <br>

            <p>{!! preg_replace('/[\r\n]+/', ' ', Session::get('alert-' . $msg)) !!}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif    
@endforeach
