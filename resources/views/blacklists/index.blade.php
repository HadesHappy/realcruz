@extends('layouts.core.frontend')

@section('title', trans('messages.blacklist'))

@section('page_header')
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.blacklist') }}</span>
        </h1>
    </div>
@endsection

@section('content')

    <div class="listing-form"
        data-url="{{ action('BlacklistController@listing') }}"
        per-page="{{ Acelle\Model\Blacklist::$itemsPerPage }}"
    >

        <div class="d-flex top-list-controls top-sticky-content">
            <div class="me-auto">
                @if ($blacklists->count() >= 0)
                    <div class="filter-box">
                        <span class="me-2">@include('helpers.select_tool')</span>
                        <div class="dropdown list_actions me-3" style="display: none">
                            <button role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                {{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" link-confirm="{{ trans('messages.remove_blacklist_confirm') }}" href="{{ action('BlacklistController@delete') }}"><span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}</a></li>
                            </ul>
                        </div>
                        <span class="filter-group">
                            <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                            <select class="select" name="sort_order">
                                <option value="blacklists.created_at">{{ trans('messages.created_at') }}</option>
                                <option value="blacklists.email">{{ trans('messages.email') }}</option>
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
            <div class="">
                @if (Auth::user()->customer->can('import', new Acelle\Model\Blacklist()))
                    <div class="text-end">
                        <a href="{{ action('BlacklistController@import') }}" role="button" class="btn btn-secondary">
                            <span class="material-symbols-rounded">
file_upload
</span> {{ trans('messages.blacklist.import') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="pml-table-container">
        </div>
    </div>

    <script>
        var BlacklistsIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('BlacklistController@listing') }}',
                    container: $('.listing-form'),
                    content: $('.pml-table-container')
                });
            }
        };

        $(document).ready(function() {
            BlacklistsIndex.getList().load();
        });
    </script>
@endsection
