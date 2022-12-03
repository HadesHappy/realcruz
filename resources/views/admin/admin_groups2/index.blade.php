@extends('layouts.core.backend')

@section('title', trans('messages.admin_groups'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.user_groups') }}</span>
        </h1>
    </div>

@endsection

@section('content')

    <div class="listing-form"
        sort-url="{{ action('Admin\AdminGroup2Controller@sort') }}"
        data-url="{{ action('Admin\AdminGroup2Controller@listing') }}"
        per-page="{{ Acelle\Model\AdminGroup::$itemsPerPage }}"
    >
        <div class="d-flex top-list-controls top-sticky-content">
            <div class="me-auto">
                @if ($groups->count() >= 0)
                    <div class="filter-box">
                        <span class="filter-group">
                            <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                            <select class="select" name="sort_order">
                                <option value="admin_groups.created_at">{{ trans('messages.created_at') }}</option>
                                <option value="admin_groups.name">{{ trans('messages.name') }}</option>
                            </select>
                            <input type="hidden" name="sort_direction" value="desc" />
<button type="button" class="btn btn-light sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                                <span class="material-symbols-rounded desc">
sort
</span>
                            </button>
                        </span>
                        <span class="text-nowrap">
                            <input type="text" name="keyword" class="form-control search" value="{{ request()->keyword }}" placeholder="{{ trans('messages.type_to_search') }}" />
                            <span class="material-symbols-rounded">
search
</span>
                        </span>
                    </div>
                @endif
            </div>
            @can('create', new Acelle\Model\AdminGroup())
                <div class="text-end">
                    <a href="{{ action("Admin\AdminGroup2Controller@create") }}" role="button" class="btn btn-secondary">
                        <span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_admin_group') }}
                    </a>
                </div>
            @endcan
        </div>

        <div class="pml-table-container"></div>
    </div>

    <script>
        var AdminGroupsIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('Admin\AdminGroup2Controller@listing') }}',
                    container: $('.listing-form'),
                    content: $('.pml-table-container')
                });
            }
        };

        $(document).ready(function() {
            AdminGroupsIndex.getList().load();
        });
    </script>

@endsection
