@if ($notifications->count() > 0)
	<table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($notifications as $key => $notification)                                    
            <tr>
                <td width="1%">
                    <div class="text-nowrap">
                        <div class="checkbox inline me-1">
                            <label>
                                <input type="checkbox" class="node styled"
                                    name="uids[]"
                                    value="{{ $notification->uid }}"
                                />
                            </label>
                        </div>
                    </div>
                </td>
                <td width="1%">
                    @if ($notification->level == \Acelle\Model\Notification::LEVEL_WARNING)
                        <span class="material-symbols-rounded bg-warning admin-notification-media text-white">
                            warning_amber
                        </span>
                    @elseif ( false &&$notification->level == \Acelle\Model\Notification::LEVEL_ERROR)
                        <span class="material-symbols-rounded bg-danger admin-notification-media text-white">
                            new_releases
                        </span>
                    @else
                        <span class="material-symbols-rounded bg-info admin-notification-media text-white">
                            lightbulb
                        </span>
                    @endif
                </td>
                <td width="40%">
                    <p class="mb-0" title="Debug: {{ $notification->debug }}">                                                
                        <span class="xtooltip tooltipstered">{!! $notification->title !!}</span><br />
                        <span class="text-muted2">{{ $notification->message }}</span>
                    </p>
                </td>
                <td class="text-end">
                    <div class="pull-right">
                        <div class="text-semibold">{{ $notification->created_at->diffForHumans() }}</div>
                        <span class="text-muted2">{{ Auth::user()->admin->formatDateTime($notification->created_at, 'date_full') }}</span>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
    @include('elements/_per_page_select', ["items" => $notifications])
                                
@elseif (!empty(request()->keyword) || !empty(request()->filters["type"]))
    <div class="empty-list">
        <i class="material-symbols-rounded">message</i> 
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else                    
    <div class="empty-list">
        <i class="material-symbols-rounded">message</i> 
        <span class="line-1">
            {{ trans('messages.no_action_notifications') }}
        </span>
    </div>
@endif