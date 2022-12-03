@extends('layouts.core.frontend')

@section('title', trans('messages.logs'))
    
@section('page_header')
    
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('messages.activities') }}</li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">
                            person_outline
                            </span> {{ Auth::user()->displayName() }}</span>
        </h1>
    </div>
                
@endsection

@section('content')
    
                @include("account._menu")    
    
                <div class="listing-form"
                    data-url="{{ action('AccountController@logsListing') }}"
                    per-page="{{ Acelle\Model\Log::$itemsPerPage }}"                
                >                
                    <div class="d-flex top-list-controls top-sticky-content">
                        <div class="me-auto">
                            @if ($logs->count() >= 0)                    
                                <div class="filter-box">
                                    <span class="filter-group">
                                        <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                                        <select class="select" name="sort_order">
                                            <option value="created_at">{{ trans('messages.created_at') }}</option>
                                        </select>                                        
                                        <input type="hidden" name="sort_direction" value="desc" />
<button type="button" class="btn btn-xs sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                                            <span class="material-symbols-rounded desc">
sort
</span>
                                        </button>
                                    </span>
                                    <span class="filter-group ml-10">
                                        <span class="title text-semibold text-muted">{{ trans('messages.type') }}</span>
                                        <select class="select" name="type">
                                            <option value="">{{ trans('messages.all') }}</option>
                                            <option value="list">{{ trans('messages.list') }}</option>
                                            <option value="segment">{{ trans('messages.segment') }}</option>
                                            <option value="page">{{ trans('messages.page') }}</option>
                                            <option value="subscriber">{{ trans('messages.subscriber') }}</option>
                                            <option value="campaign">{{ trans('messages.campaign') }}</option>
                                        </select>                                        
                                    </span>
                                    <!--<span class="text-nowrap">
                                        <input type="text" name="keyword" class="form-control search" value="{{ request()->keyword }}" placeholder="{{ trans('messages.type_to_search') }}" />
                                        <span class="material-symbols-rounded">
search
</span>
                                    </span>-->
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="pml-table-container">
                        
                        
                        
                    </div>
                </div>

                <script>
                    var AccountLogsIndex = {
                        getList: function() {
                            return makeList({
                                url: '{{ action('AccountController@logsListing') }}',
                                container: $('.listing-form'),
                                content: $('.pml-table-container')
                            });
                        }
                    };
            
                    $(document).ready(function() {
                        AccountLogsIndex.getList().load();
                    });
                </script>
    
@endsection