@extends('layouts.core.frontend')

@section('title', trans('messages.tracking_domains'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("TrackingDomainController@index") }}">{{ trans('messages.tracking_domains') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold">{{ trans('messages.tracking_domains') }}</span>
        </h1>           
    </div>

@endsection

@section('content')
    
	<div class="row">
        <div class="col-sm-12 col-md-10 col-lg-10">
            <p>{!! trans('messages.tracking_domain.wording') !!}</p>
        </div>
    </div>

    <div class="listing-form"
        data-url="{{ action('TrackingDomainController@listing') }}"
        per-page="{{ Acelle\Model\TrackingDomain::$itemsPerPage }}"
    >
        <div class="d-flex top-list-controls top-sticky-content">
            <div class="me-auto">
                @if ($trackingDomains->count() >= 0)
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
                                <li><a class="dropdown-item" link-confirm="{{ trans('messages.delete_tracking_domains_confirm') }}" href="{{ action('TrackingDomainController@delete') }}"><span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}</a></li>
                            </ul>
                        </div>
                        
                        <span class="filter-group">
                            <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                            <select class="select" name="sort_order">
                                <option value="tracking_domains.name">{{ trans('messages.name') }}</option>
                                <option value="tracking_domains.created_at">{{ trans('messages.created_at') }}</option>
                                <option value="tracking_domains.updated_at">{{ trans('messages.updated_at') }}</option>
                            </select>
                            <input type="hidden" name="sort_direction" value="asc" />
                                                <button class="btn btn-xs sort-direction" rel="asc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
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
            @if (Auth::user()->customer->can('create', new Acelle\Model\TrackingDomain()))
                <div class="text-end">
                    <a href="{{ action('TrackingDomainController@create') }}" role="button" class="btn btn-secondary">
                        <span class="material-symbols-rounded">
add
</span> {{ trans('messages.tracking_domain.create') }}
                    </a>
                </div>
            @endif
        </div>

        <div class="pml-table-container"></div>
    </div>

    <script>
        var TrackingDomainsIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('TrackingDomainController@listing') }}',
                    container: $('.listing-form'),
                    content: $('.pml-table-container')
                });
            }
        };

        $(document).ready(function() {
            TrackingDomainsIndex.getList().load();
        });
    </script>
@endsection
