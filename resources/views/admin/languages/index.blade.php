@extends('layouts.core.backend')

@section('title', trans('messages.languages'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.languages') }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <p>{{ trans('messages.languages.wording') }}</p>
    <div class="listing-form"
        data-url="{{ action('Admin\LanguageController@listing') }}"
        per-page="{{ Acelle\Model\SendingDomain::$itemsPerPage }}"
    >
        <div class="d-flex top-list-controls top-sticky-content">
            <div class="me-auto">
                <div class="filter-box">
                    <span class="filter-group">
                        <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                        <select class="select" name="sort_order">
                            <option value="languages.name">{{ trans('messages.name') }}</option>
                            <option value="languages.code">{{ trans('messages.code') }}</option>
                            <option value="languages.region_code">{{ trans('messages.region') }}</option>
                            <option value="languages.created_at">{{ trans('messages.created_at') }}</option>
                            <option value="languages.updated_at">{{ trans('messages.updated_at') }}</option>
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
            </div>
            @can("create", new Acelle\Model\Language())
                <div class="text-end">
                    <a href="{{ action('Admin\LanguageController@create') }}" role="button" class="btn btn-secondary">
                        <span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_language') }}
                    </a>
                </div>
            @endcan
        </div>

        <div class="pml-table-container"></div>
    </div>

    <script>
        var LanguagesIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('Admin\LanguageController@listing') }}',
                    container: $('.listing-form'),
                    content: $('.pml-table-container')
                });
            }
        };

        $(function() {
            LanguagesIndex.getList().load();
        });
    </script>
@endsection
