@extends('layouts.core.frontend')

@section('title', trans('messages.stores_connections'))

@section('page_header')
	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
		</ul>
		<h1>
			<span class="text-semibold"><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.stores_connections') }}</span>
		</h1>
	</div>
@endsection

@section('content')
    <div id="SourcesIndexContainer" class="listing-form"
        data-url="{{ action('Site\SourceController@listing') }}"
        per-page="{{ Acelle\Model\Source::$itemsPerPage }}"
    >
        <div class="d-flex top-list-controls top-sticky-content">
            <div class="me-auto">
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
                    <span class="text-nowrap">
                        <input type="text" name="keyword" class="form-control search" value="{{ request()->keyword }}" placeholder="{{ trans('messages.type_to_search') }}" />
                        <span class="material-symbols-rounded">
search
</span>
                    </span>
                </div>
            </div>
            <div class="text-end">
                <a href="{{ action("Site\SourceController@create") }}" role="button" class="btn btn-secondary m-icon">
                    <span class="material-symbols-rounded">add</span> {{ trans('messages.source.add_new') }}
                </a>
            </div>
        </div>

        <div id="SourcesIndexContent" class="pml-table-container"></div>
    </div>

    <script>
        var SourcesIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('Site\SourceController@listing') }}',
                    container: $('#SourcesIndexContainer'),
                    content: $('#SourcesIndexContent')
                });
            }
        };

        $(document).ready(function() {
            SourcesIndex.getList().load();
        });
    </script>
@endsection
