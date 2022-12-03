@if ($groups->count() > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($groups as $key => $item)
            <tr>
                <td>
                    <h5 class="m-0 text-bold">
                        <a class="kq_search d-block" href="{{ action('Admin\AdminGroup2Controller@edit', $item->id) }}">{{ $item->name }}</a>
                    </h5>
                    <span class="text-muted">{{ trans('messages.created_at') }}: {{ Auth::user()->admin->formatDateTime($item->created_at, 'date_full') }}</span>
                </td>
                <td>
                    <div class="single-stat-box pull-left">
                        <span class="no-margin stat-num">{{ $item->admins()->count() }}</span>
                        <br />
                        <span class="text-muted">{{ trans("messages.users") }}</span>
                    </div>
                </td>
                <td class="text-end">
                    <span class="text-muted2 list-status pull-left">
                        <span class="label label-flat bg-{{ $item->status }}">{{ $item->status }}</span>
                    </span>
                    @can('update', $item)
                        <a href="{{ action('Admin\AdminGroup2Controller@edit', $item->id) }}" role="button" class="btn btn-secondary btn-icon"><i class="icon icon-pencil"></i> {{ trans('messages.edit') }}</a>
                    @endcan
                    @can('delete', $item)
                        <div class="btn-group">
                            <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a
                                        class="dropdown-item list-action-single"
                                        link-confirm="{{ trans('messages.delete_admin_groups_confirm') }}"
                                        href="{{ action('Admin\AdminGroup2Controller@delete', ['ids' => $item->id]) }}">
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
    @include('elements/_per_page_select', ["items" => $groups])
    
@elseif (!empty(request()->keyword))
    <div class="empty-list">
        <span class="material-symbols-rounded">
people
</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <span class="material-symbols-rounded">
people
</span>
        <span class="line-1">
            {{ trans('messages.admin_group_empty_line_1') }}
        </span>
    </div>
@endif
