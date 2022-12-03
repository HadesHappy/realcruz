@if ($items->count() > 0)
<table class="table table-box pml-table table-log"
    current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
>
    <tr>
        <th>{{ trans('messages.recipient') }}</th>
        <th>{{ trans('messages.feedback_type') }}</th>
        <th>{{ trans('messages.raw_feedback_content') }}</th>
        <th>{{ trans('messages.campaign') }}</th>
        <th>{{ trans('messages.sending_server') }}</th>
        <th>{{ trans('messages.created_at') }}</th>
    </tr>
    @foreach ($items as $key => $item)
        <tr>
            <td>
                <span class="no-margin kq_search">{{ $item->trackingLog->subscriber->email }}</span>
                <span class="text-muted second-line-mobile">{{ trans('messages.recipient') }}</span>
            </td>
            <td>
                <span class="no-margin kq_search">{{ $item->feedback_type }}</span>
                <span class="text-muted second-line-mobile">{{ trans('messages.feedback_type') }}</span>
            </td>
            <td>
                <span class="no-margin kq_search">{{ $item->raw_feedback_content }}</span>
                <span class="text-muted second-line-mobile">{{ trans('messages.raw_feedback_content') }}</span>
            </td>
            <td>
                <span class="no-margin kq_search">{{ is_null($item->trackingLog->campaign) ? 'N/A' : $item->trackingLog->campaign->name }}</span>
                <span class="text-muted second-line-mobile">{{ trans('messages.campaign') }}</span>
            </td>
            <td>
                <span class="no-margin kq_search">{{ $item->trackingLog->sendingServer->name }}</span>
                <span class="text-muted second-line-mobile">{{ trans('messages.sending_server') }}</span>
            </td>
            <td>
                <span class="no-margin kq_search">{{ Auth::user()->customer->formatDateTime($item->created_at, 'date_full') }}</span>
                <span class="text-muted second-line-mobile">{{ trans('messages.created_at') }}</span>
            </td>
        </tr>
    @endforeach
</table>
@include('elements/_per_page_select')

@elseif (!empty(request()->keyword) || !empty(request()->filters["campaign_uid"]))
<div class="empty-list">
    <span class="material-symbols-rounded">
auto_awesome
</span>
    <span class="line-1">
        {{ trans('messages.no_search_result') }}
    </span>
</div>
@else
<div class="empty-list">
    <span class="material-symbols-rounded">
auto_awesome
</span>
    <span class="line-1">
        {{ trans('messages.log_empty_line_1') }}
    </span>
</div>
@endif
