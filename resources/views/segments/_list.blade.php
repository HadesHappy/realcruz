@if ($segments->count() > 0)
    <table class="table table-box pml-table mt-2"
           current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($segments as $key => $item)
            <tr>
                <td width="1%">
                    <div class="text-nowrap">
                        <div class="checkbox inline me-1">
                            <label>
                                <input type="checkbox" class="node styled"
                                       name="uids[]"
                                       value="{{ $item->uid }}"
                                />
                            </label>
                        </div>
                    </div>
                </td>
                <td>
                    <a class="kq_search text-bold d-block"
                        href="{{ action('SegmentController@subscribers', ['list_uid' => $list->uid, 'uid' => $item->uid]) }}">
                        {{ $item->name }}
                    </a>
                    <span class="text-muted">{{ trans('messages.created_at') }}
                        : {{ Auth::user()->customer->formatDateTime($item->created_at, 'date_full') }}</span>
                </td>
                <td>
                    <div class="single-stat-box pull-left">

                        <a class="kq_search"
                           href="{{ action('SegmentController@subscribers', ['list_uid' => $list->uid, 'uid' => $item->uid]) }}">
                            <span class="no-margin stat-num">{{ number_with_delimiter($item->readCache('SubscriberCount', '#')) }}</span>
                        </a>
                        <br/>
                        <span class="text-muted">{{ trans("messages.subscribers") }}</span>
                    </div>
                </td>

                <td class="text-end text-nowrap pe-0">
                    @if (\Gate::allows('update', $item))
                        <a href="{{ action('SegmentController@edit', ['list_uid' => $list->uid, "uid" => $item->uid]) }}"
                           role="button" class="btn btn-secondary btn-icon">
                            <span class="material-symbols-rounded">
edit
</span> {{ trans('messages.edit') }}
                        </a>
                    @endif
                    <div class="btn-group">
                        <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"><span
                                    class="caret ml-0"></span></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if (\Gate::allows('delete', $item))
                                <li><a class="dropdown-item list-action-single"
                                       link-confirm="{{ trans('messages.delete_segments_confirm') }}"
                                       href="{{ action('SegmentController@delete', ['list_uid' => $list->uid, "uids" => $item->uid]) }}"><i
                                                class="icon-trash"></i> {{ trans("messages.delete") }}</a></li>
                            @endif
                            @if (\Gate::allows('export', $item))
                                <li>
                                    <a class="dropdown-item" href="{{ action('SubscriberController@export', [
                                        'list_uid' => $list->uid,
                                        'segment_uid' => $item->uid,
                                    ]) }}"><i
                                                class="icon-upload4"></i> {{ trans("messages.export") }}</a></li>
                            @endif
                        </ul>
                    </div>
                </td>

            </tr>
        @endforeach
    </table>
    @include('elements/_per_page_select', ["items" => $segments])
    
@elseif (!empty(request()->keyword))
    <div class="empty-list">
        <span class="material-symbols-rounded">
splitscreen
</span>
		<span class="line-1">
			{{ trans('messages.no_search_result') }}
		</span>
    </div>
@else
    <div class="empty-list">
        <span class="material-symbols-rounded">
splitscreen
</span>
		<span class="line-1">
			{{ trans('messages.segment_empty_line_1') }}
		</span>
    </div>
@endif
