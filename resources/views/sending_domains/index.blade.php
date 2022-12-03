@extends('layouts.core.frontend')

@section('title', trans('messages.sending_domains'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("SenderController@index") }}">{{ trans('messages.verified_senders') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("SendingDomainController@index") }}">{{ trans('messages.domains') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold">{{ trans('messages.verified_senders') }}</span>
        </h1>           
    </div>

@endsection

@section('content')
    
    @include('senders._menu')
    
    <h2>
        <span class="text-semibold">{{ trans('messages.domains') }}</span>
    </h2>
    
	<div class="row">
        <div class="col-sm-12 col-md-10 col-lg-10">
            <p>{!! trans('messages.sending_domain.wording') !!}</p>
        </div>
    </div>

    <form class="listing-form"
        sort-url="{{ action('SendingDomainController@sort') }}"
        data-url="{{ action('SendingDomainController@listing') }}"
        per-page="{{ Acelle\Model\SendingDomain::$itemsPerPage }}"
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
                                <li><a class="dropdown-item" link-confirm="{{ trans('messages.delete_sending_domains_confirm') }}" href="{{ action('SendingDomainController@delete') }}"><span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}</a></li>
                            </ul>
                        </div>
                        <span class="filter-group">
                            <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                            <select class="select" name="sort_order">
                                <option value="sending_domains.created_at">{{ trans('messages.created_at') }}</option>
                                <option value="sending_domains.name">{{ trans('messages.name') }}</option>
                                <option value="sending_domains.updated_at">{{ trans('messages.updated_at') }}</option>
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
            @if (Auth::user()->customer->can('create', new Acelle\Model\SendingDomain()))
                <div class="text-end">
                    <a href="{{ action('SendingDomainController@create') }}" role="button" class="btn btn-secondary">
                        <span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_sending_domain') }}
                    </a>
                </div>
            @endif
        </div>

        <div class="pml-table-container"></div>
    </form>


    <script>
        var SendingDomainsIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('SendingDomainController@listing') }}',
                    container: $('.listing-form'),
                    content: $('.pml-table-container')
                });
            }
        };

        $(function() {
            SendingDomainsIndex.getList().load();
        });
    </script>
@endsection
