@extends('layouts.core.backend')

@section('title', trans('messages.customers'))

@section('page_header')

			<div class="page-title">				
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.customers') }}</span>
				</h1>				
			</div>

@endsection

@section('content')				
	<div class="listing-form"
		sort-url="{{ action('Admin\CustomerController@sort') }}"
		data-url="{{ action('Admin\CustomerController@listing') }}"
		per-page="{{ Acelle\Model\Customer::$itemsPerPage }}"					
	>				
		<div class="d-flex top-list-controls top-sticky-content">
			<div class="me-auto">
				@if ($customers->count() >= 0)					
					<div class="filter-box">
						<span class="filter-group">
							<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
							<select class="select" name="sort_order">											
								<option value="customers.created_at">{{ trans('messages.created_at') }}</option>
								<option value="customers.updated_at">{{ trans('messages.updated_at') }}</option>
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
				@endif
			</div>
			@can('create', new Acelle\Model\Customer())
				<div class="text-end">
					<a href="{{ action("Admin\CustomerController@create") }}" role="button" class="btn btn-secondary">
						<span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_customer') }}
					</a>
				</div>
			@endcan
		</div>
		
		<div class="pml-table-container">
			
			
			
		</div>
	</div>

	<script>
		var assignPlanModal = new IframeModal();
	</script>

	<script>
		var CustomersIndex = {
			getList: function() {
				return makeList({
					url: '{{ action('Admin\CustomerController@listing') }}',
					container: $('.listing-form'),
					content: $('.pml-table-container')
				});
			}
		};

		$(document).ready(function() {
			CustomersIndex.getList().load();
		});
	</script>

@endsection
