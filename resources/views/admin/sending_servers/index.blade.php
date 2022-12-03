@extends('layouts.core.backend')

@section('title', trans('messages.sending_servers'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.sending_servers') }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <div class="alert alert-info">
        <span class="text-semibold">{{ trans('messages.notification.note') }} </span> {{ trans('messages.notification.sending_servers') }}
    </div>
    <p>{{ trans('messages.sending_server.wording') }}</p>


    <div class="listing-form"
        sort-url="{{ action('Admin\SendingServerController@sort') }}"
        data-url="{{ action('Admin\SendingServerController@listing') }}"
        per-page="{{ Acelle\Model\SendingServer::$itemsPerPage }}"
    >
        <div class="d-flex top-list-controls top-sticky-content">
            <div class="me-auto">
                @if ($items->count() >= 0)
                    <div class="filter-box">
                        <div class="checkbox inline check_all_list">
                            <label>
                                <input type="checkbox" name="page_checked" class="styled check_all">
                            </label>
                        </div>
                        <div class="dropdown list_actions" style="display: none">
                            <button role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                {{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" link-confirm="{{ trans('messages.enable_sending_servers_confirm') }}" href="{{ action('Admin\SendingServerController@enable') }}"><span class="material-symbols-rounded">
play_arrow
</span> {{ trans('messages.enable') }}</a></li>
                                <li><a class="dropdown-item" link-confirm="{{ trans('messages.disable_sending_servers_confirm') }}" href="{{ action('Admin\SendingServerController@disable') }}"><span class="material-symbols-rounded">
hide_source
</span> {{ trans('messages.disable') }}</a></li>
                                <li><a class="dropdown-item" link-confirm="{{ trans('messages.delete_sending_servers_confirm') }}" href="{{ action('Admin\SendingServerController@delete') }}"><span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}</a></li>
                            </ul>
                        </div>
                        <span class="filter-group">
                            <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                            <select class="select" name="sort_order">
                                <option value="sending_servers.created_at">{{ trans('messages.created_at') }}</option>
                                <option value="sending_servers.name">{{ trans('messages.name') }}</option>
                                <option value="sending_servers.updated_at">{{ trans('messages.updated_at') }}</option>
                            </select>
                            <input type="hidden" name="sort_direction" value="desc" />
<button type="button" class="btn btn-light sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                                <span class="material-symbols-rounded desc">
sort
</span>
                            </button>
                        </span>
                        <span class="filter-group">
                            <span class="title text-semibold text-muted">{{ trans('messages.type') }}</span>
                            <select class="select" name="type">
                                <option value="">{{ trans('messages.all') }}</option>
                                @foreach (Acelle\Model\SendingServer::types() as $key => $type)
                                    <option value="{{ $key }}">{{ trans('messages.' . $key) }}</option>
                                @endforeach
                            </select>
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
            @if (Auth::user()->admin->can('create', Acelle\Model\SendingServer::class))
                <div class="text-end">
                    <a href="{{ action('Admin\SendingServerController@select') }}" role="button" class="btn btn-secondary">
                        <span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_sending_server') }}
                    </a>
                </div>
            @endif
        </div>

        <div class="pml-table-container">
        </div>
    </div>

    <script>
        var SendingServersIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('Admin\SendingServerController@listing') }}',
                    container: $('.listing-form'),
                    content: $('.pml-table-container')
                });
            }
        };

        $(function() {
            SendingServersIndex.getList().load();
        });
    </script>
@endsection
