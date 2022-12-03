<div class="d-flex">
    <h3 class="me-auto">
        <div class="mb-4">
            {{ trans('messages.activities') }}
        </div>
    </h3>
    <div>
        <a href="{{ action("AccountController@logs") }}" class="btn btn-info">{{ trans('messages.all_activities') }}</a>
    </div>
</div>  

@if (Auth::user()->customer->logs()->count() == 0)
    <div class="empty-list">
        <span class="material-symbols-rounded">
            auto_awesome
        </span>
        <span class="line-1">
            {{ trans('messages.no_activity_logs') }}
        </span>
    </div>
@else
    <div class="action-log-box">
        <!-- Timeline -->
        <div class="">
            <div class="">
                @foreach (Auth::user()->customer->logs()->take(20)->get() as $log)
                    <!-- Sales stats -->
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <a href="{{ action('AccountController@profile') }}"><img width="40px" class="rounded-circle shadow-sm" src="{{ $log->customer->user->getProfileImageUrl() }}" alt=""></a>
                        </div>

                        <div class="card px-0 shadow-sm container-fluid">
                            <div class="card-body pt-2">
                                <div class="d-flex align-items-center pt-1">
                                    <label class="panel-title text-semibold my-0 fw-600">{{ $log->customer->user->displayName() }}</label>
                                    <div class="d-flex align-items-center ms-auto text-muted small">
                                        <span class="material-symbols-rounded ms-auto me-1 small">
                                            history
                                        </span>
                                        <div class="">
                                            <span class="heading-text"><i class="icon-history position-left text-success"></i> {{ $log->created_at->timezone($currentTimezone)->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 mt-1 small">{!! $log->message() !!}</p>
                            </div>
                        </div>
                    </div>
                    <!-- /sales stats -->
                @endforeach
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ action("AccountController@logs") }}" class="btn btn-info">{{ trans('messages.all_activities') }}</a>
    </div>
@endif