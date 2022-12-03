@if ($items->count() > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($items as $key => $item)
            <tr>
                <td width="1%">
                    <i class="material-symbols-rounded fs-3 me-2 text-muted">{{ $item->type == 'page' ? "web" : "email" }}</i>
                </td>
                <td>
                    <h5 class="m-0 text-bold">
                        <a class=" d-block" href="{{ action('Admin\LayoutController@edit', $item->uid) }}">
                            {{ trans('messages.' . $item->alias) }}
                        </a>
                    </h5>
                    <p>{{ $item->group_name }}</p>
                </td>
                <td>
                    <div class="single-stat-box pull-left">
                        <span class="no-margin stat-num">{{ trans('messages.' . $item->type) }}</span>
                        <br />
                        <span class="text-muted2">{{ trans("messages.display_type") }}</span>
                    </div>
                </td>
                <td>
                    <div class="single-stat-box pull-left">
                        <span class="no-margin stat-num">{{ $item->pages()->count() }}</span>
                        <br />
                        <span class="text-muted2">{{ trans("messages.custom_pages") }}</span>
                    </div>
                </td>
                <td class="text-end">
                    @can('update', $item)
                        <a href="{{ action('Admin\LayoutController@edit', $item->uid) }}" role="button" class="btn btn-secondary btn-icon"> <span class="material-symbols-rounded">
edit
</span> {{ trans('messages.edit') }}</a>
                    @endcan
                </td>
            </tr>
        @endforeach
    </table>
    @include('elements/_per_page_select')
    
@elseif (!empty(request()->keyword))
    <div class="empty-list">
        <i class="icon icon-file"></i>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@endif
