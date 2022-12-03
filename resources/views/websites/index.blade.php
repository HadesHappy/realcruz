@extends('layouts.core.frontend')

@section('title', trans('messages.campaigns'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.websites') }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <div id="WebsitesIndexContainer" class="listing-form top-sticky"
        data-url="{{ action('WebsiteController@list') }}"
    >
        <div class="d-flex top-list-controls top-sticky-content">
            <div class="me-auto">
                <div class="filter-box">
                    <div class="checkbox inline check_all_list">
                        <label>
                            <input type="checkbox" name="page_checked" class="styled check_all">
                        </label>
                    </div>
                    <div class="dropdown list_actions" style="display: none">
                        <button type="button"
                            id="dropdownListActions"
                            class="btn btn-secondary dropdown-toggle"
                            data-bs-toggle="dropdown"
                        >
                            {{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownListActions">
                            <li>
                                <a class="action dropdown-item"
                                link-method="POST"
                                link-confirm="{{ trans('messages.websites.disconnect.confirm') }}"
                                href="{{ action('WebsiteController@disconnect') }}">
                                    <span class="material-symbols-rounded me-2"> pause_circle </span>
                                    {{ trans('messages.website.disconnect') }}</a>
                            </li>
                            <li>
                                <a class="action dropdown-item"
                                link-method="POST"
                                link-confirm="{{ trans('messages.websites.delete.confirm') }}"
                                href="{{ action('WebsiteController@delete') }}">
                                    <span class="material-symbols-rounded me-2">delete_outline</span>
                                    {{ trans('messages.delete') }}</a>
                            </li>
                        </ul>
                    </div>
                    <span class="filter-group">
                        <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                        <select class="select" name="sort_order">
                            <option value="created_at">{{ trans('messages.created_at') }}</option>
                            <option value="name">{{ trans('messages.name') }}</option>
                        </select>
                        <input type="hidden" name="sort_direction" value="desc" />
<button type="button" class="btn btn-light sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                            <span class="material-symbols-rounded desc">
sort
</span>
                        </button>
                    </span>
                    <span class="text-nowrap">
                        <input type="text" name="keyword" class="form-control search" value="{{ request()->keyword }}" value="{{ request()->keyword }}" placeholder="{{ trans('messages.type_to_search') }}" />
                        <span class="material-symbols-rounded">
search
</span>
                    </span>
                </div>
            </div>
            <div class="text-end">
                <a href="{{ action('WebsiteController@create') }}" role="button" class="btn btn-secondary">
                    <span class="material-symbols-rounded">
add
</span> {{ trans('messages.website.add') }}
                </a>
            </div>
        </div>

        <div id="WebsitesIndexContent" class="pml-table-container">



        </div>
    </div>

    <script>
        var WebsitesIndex = {
            list: null,
            getList: function() {
                if (this.list == null) {
                    this.list = makeList({
                        url: '{{ action('WebsiteController@list') }}',
                        container: $('#WebsitesIndexContainer'),
                        content: $('#WebsitesIndexContent')
                    });
                }
                return this.list;
            }
        };

        $(document).ready(function() {
            WebsitesIndex.getList().load();
        });
    </script>
@endsection
