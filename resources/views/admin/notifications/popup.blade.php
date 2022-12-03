<div class="d-flex mb-3">
    <h3 class="me-auto">
        <div class="">
            {{ trans('messages.notifications') }}
        </div>
    </h3>
    <div>
        <a href="{{ action("Admin\NotificationController@index") }}" class="btn btn-info">{{ trans('messages.all_logs') }}</a>
    </div>
</div>    
<ul class="notifications-list pl-0">
    @if (Auth::user()->admin && Auth::user()->admin->notifications()->count() == 0)
        <li>
            <div class="text-center py-2 d-block">
                <span class="material-symbols-rounded fs-2 text-muted mb-2">
                    running_with_errors
                </span>
                <div class="text-muted">
                    {{ trans('messages.no_activity_logs') }}
                </div>
            </div>
        </li>
    @endif
    @foreach (Auth::user()->admin->notifications()->take(20)->get() as $notification)
        <li class="mb-3 px-3 py-2 bg-white shadow-sm rounded-3">
            <div class="d-flex py-2" hrxef="javascript:;">
                <i class="me-4 d-block">
                    @if ($notification->level == \Acelle\Model\Notification::LEVEL_WARNING)
                    <span class="material-symbols-rounded bg-warning notification-icon">
                        warning_amber
                        </span>
                    @elseif ( false &&$notification->level == \Acelle\Model\Notification::LEVEL_ERROR)
                    <span class="material-symbols-rounded bg-danger notification-icon">
                        new_releases
                        </span>
                    @else
                    <span class="material-symbols-rounded bg-info notification-icon">
                        lightbulb
                        </span>
                    @endif
                </i>
                <div class="d-block position-relative" style="">
                    <span class="mb-2">
                        <span class="text-semibold me-auto" style="white-space: initial;">
                            <span class="fw-600">{{ $notification->title }}</span>
                            <span class="text-muted small d-block mt-1 mb-2 text-muted2"><span class="material-symbols-rounded">
                                restore
                                </span> {{ $notification->created_at->diffForHumans() }}</span>
                        </span>
                        
                    </span>
                    <p class="desc-menu-log small mb-0" style="width:280px;overflow:auto">{{ $notification->message }}</p>
                </div>
            </div>
        </li>
    @endforeach
</ul>
@if (Auth::user()->admin->notifications()->count() > 0)
    <div class="text-center mt-4">
        <a href="{{ action("Admin\NotificationController@index") }}" class="btn btn-info">{{ trans('messages.all_logs') }}</a>
    </div>
@endif