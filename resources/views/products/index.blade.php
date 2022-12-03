@extends('layouts.core.frontend')

@section('title', trans('messages.products'))

@section('page_header')
	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
		</ul>
		<h1>
			<span class="text-semibold"><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.products') }}</span>
		</h1>
	</div>
@endsection

@section('content')
    <div id="ProductsIndexContainer" class="listing-form view-{{ request()->view ? request()->view : 'grid' }}"
        data-url="{{ action('ProductController@listing', ['view' => request()->view]) }}"
        per-page="{{ Acelle\Model\Product::$itemsPerPage }}"
    >
        <div class="d-flex top-list-controls top-sticky-content">
            <div class="me-auto">
                <div class="filter-box">
                    <input type="hidden" name="view" value="{{ request()->view }}" />
                    <span class="filter-group">
                        <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                        <select class="select" name="sort_order">
                            <option value="created_at">{{ trans('messages.created_at') }}</option>
                            <option value="title">{{ trans('messages.product.title') }}</option>
                        </select>
                        <input type="hidden" name="sort_direction" value="desc" />
<button type="button" class="btn btn-xs sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                            <span class="material-symbols-rounded desc">
sort
</span>
                        </button>
                    </span>
                    <span class="filter-group">
                        <span class="title text-semibold text-muted">{{ trans('messages.source') }}</span>
                        <select class="select" name="source_uid">
                            <option value="" class="active">{{ trans('messages.all_source') }}</option>
                            @foreach (Acelle\Model\Source::all() as $source)
                                <option {!! request()->source_uid == $source->uid ? 'selected' : '' !!} value="{{ $source->uid }}" class="active">{{ $source->getName() }}</option>
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
            </div>
            <div class="text-end d-flex align-items-center">
                <div class="view-toggle d-flex ml-auto">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="{{ action('ProductController@index') }}" class="btn btn-default view-toogle grid m-icon">
                            <span class="material-symbols-rounded">
                                grid_view
                            </span>
                        </a>
                        <a href="{{ action('ProductController@index', ['view' => 'list']) }}" class="btn btn-default view-toogle list m-icon mr-3">
                            <span class="material-symbols-rounded">
                                reorder
                            </span>
                        </a>
                    </div>
                </div>
                <a href="{{ action("SourceController@index") }}" role="button" class="btn btn-secondary m-icon">
                    <span class="material-symbols-rounded">store</span> {{ trans('messages.stores_connections') }}
                </a>
            </div>
        </div>

        <div id="ProductsIndexContent" class="pml-table-container"></div>
    </div>

    <script>
        var ProductsIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('ProductController@listing') }}',
                    container: $('#ProductsIndexContainer'),
                    content: $('#ProductsIndexContent')
                });
            }
        };

        $(document).ready(function() {
            ProductsIndex.getList().load();
        });
    </script>
@endsection
