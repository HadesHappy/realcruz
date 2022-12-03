@extends('layouts.core.frontend')

@section('title', trans('messages.verified_senders'))

@section('page_header')
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("SenderController@index") }}">{{ trans('messages.verified_senders') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("SenderController@index") }}">{{ trans('messages.email_addresses') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold">{{ trans('messages.verified_senders') }}</span>
        </h1>    
    </div>
@endsection

@section('content')
    
    @include('senders._menu')
    
    <h2 class="text-semibold">{{ trans('messages.email_addresses') }}</h2>
    
    <p>{{ trans('messages.sender.wording') }}</p>

    <div class="listing-form"
        data-url="{{ action('SenderController@listing') }}"
        per-page="{{ Acelle\Model\Sender::$itemsPerPage }}"
    >

        <div class="d-flex top-list-controls top-sticky-content">
            <div class="me-auto">
                @if ($senders->count() >= 0)
                    <div class="filter-box">
                        <span class="me-2">@include('helpers.select_tool')</span>
                        <div class="dropdown list_actions me-3" style="display: none">
                            <button role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                {{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" link-confirm="{{ trans('messages.remove_blacklist_confirm') }}" href="{{ action('SenderController@delete') }}"><span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}</a></li>
                            </ul>
                        </div>
                        <span class="filter-group">
                            <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                            <select class="select" name="sort_order">
                                <option value="senders.created_at">{{ trans('messages.created_at') }}</option>
                                <option value="senders.email">{{ trans('messages.email') }}</option>
                                <option value="senders.email">{{ trans('messages.name') }}</option>
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
                @if (Auth::user()->can('create', new Acelle\Model\Sender()))
                    <div class="text-end">
                        <a href="{{ action('SenderController@create') }}" role="button" class="btn btn-secondary">
                            <span class="material-symbols-rounded">
file_upload
</span> {{ trans('messages.sender.create') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="pml-table-container">
        </div>
    </div>

    <script>
        var SendersIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('SenderController@listing') }}',
                    container: $('.listing-form'),
                    content: $('.pml-table-container')
                });
            }
        };

        $(function() {
            SendersIndex.getList().load();
        });
    </script>
@endsection
