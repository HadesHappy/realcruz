@if ($items->count() > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($items as $key => $item)
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
                    <h5 class="m-0 text-bold">
                        <a class="kq_search d-block" href="{{ action('Admin\BounceHandlerController@edit', $item->uid) }}">{{ $item->name }}</a>
                    </h5>
                    <span class="text-muted">{{ trans('messages.created_at') }}: {{ Auth::user()->admin->formatDateTime($item->created_at, 'date_full') }}</span>
                </td>
                <td>
                    <span class="no-margin stat-num kq_search">{{ $item->host }}</span>
                    <br />
                    <span class="text-muted">{{ trans('messages.host') }}</span>
                </td>
                <td>
                    <span class="no-margin stat-num kq_search">{{ $item->username }}</span>
                    <br />
                    <span class="text-muted">{{ trans('messages.username') }}</span>
                </td>
                <td class="text-center">
                    <span class="text-muted2 list-status pull-left">
                        <span class="label label-flat bg-{{ $item->status }}">{{ trans('messages.bounce_handler_status_' . $item->status) }}</span>
                    </span>
                </td>
                <td class="text-end">
                    
                    @can('update', $item)
                        <a href="{{ action('Admin\BounceHandlerController@edit', $item->uid) }}" data-popup="tooltip" title="{{ trans('messages.edit') }}" role="button" class="btn btn-secondary btn-icon"><span class="material-symbols-rounded">
edit
</span> {{ trans('messages.edit') }}</a>
                    @endcan
                    @can('delete', $item)
                        <div class="btn-group">                                        
                            <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">                                                    
                                <li>                                                        
                                    <a class="dropdown-item list-action-single" link-confirm="{{ trans('messages.delete_bounce_handlers_confirm') }}" href="{{ action('Admin\BounceHandlerController@delete', ["uids" => $item->uid]) }}">
                                        <span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endcan
                </td>
            </tr>
        @endforeach
    </table>
    @include('elements/_per_page_select', ["items" => $items])
    
@elseif (!empty(request()->keyword) || !empty(request()->filters["type"]))
    <div class="empty-list">
        <span class="material-symbols-rounded">
restart_alt
</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else                    
    <div class="empty-list">
        <span class="material-symbols-rounded">
restart_alt
</span>
        <span class="line-1">
            {{ trans('messages.bounce_handler_empty_line_1') }}
        </span>
    </div>
@endif