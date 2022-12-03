@if ($logs->count() > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($logs as $key => $item)
            <tr>
                <td width="1%">
                    <img width="50" class="rounded-circle me-2" src="{{ $item->customer->user->getProfileImageUrl() }}" alt="">
                </td>
                <td style="width:50%">
                    <p class="mb-0">                                                
                        {!! $item->message() !!}<br />
                        <span class="text-muted2">{{ trans('messages.' . $item->type) }}</span>
                    </p>
                </td>
                <td class="text-end">
                    <div class="pull-right">
                        <div class="text-semibold">{{ $item->created_at->diffForHumans() }}</div>
                        <span class="text-muted2">{{ Auth::user()->customer->formatDateTime($item->created_at, 'date_full') }}</span>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
    @include('elements/_per_page_select', ["items" => $logs])
                                
@elseif (!empty(request()->keyword) || !empty(request()->filters["type"]))
    <div class="empty-list">
        <span class="material-symbols-rounded">
history_toggle_off
</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else                    
    <div class="empty-list">
        <span class="material-symbols-rounded">
history_toggle_off
</span>
        <span class="line-1">
            {{ trans('messages.no_action_logs') }}
        </span>
    </div>
@endif