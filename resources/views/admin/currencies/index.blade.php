@extends('layouts.core.backend')

@section('title', trans('messages.currencies'))

@section('page_header')

			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.currencies') }}</span>
				</h1>
			</div>

@endsection

@section('content')

				<div class="listing-form"
					sort-url="{{ action('Admin\CurrencyController@sort') }}"
					data-url="{{ action('Admin\CurrencyController@listing') }}"
					per-page="{{ Acelle\Model\Admin::$itemsPerPage }}"
				>
					<div class="d-flex top-list-controls top-sticky-content">
						<div class="me-auto">
							@if ($currencies->count() >= 0)
								<div class="filter-box">
									<div class="checkbox inline check_all_list">
										<label>
											<input type="checkbox" name="page_checked" class="styled check_all">
										</label>
									</div>
									<div class="btn-group list_actions me-2" style="display:none">
										<button role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
											{{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											<li><a class="dropdown-item" link-confirm="{{ trans('messages.enable_currencies_confirm') }}" href="{{ action('Admin\CurrencyController@enable') }}"><span class="material-symbols-rounded">
play_arrow
</span> {{ trans('messages.enable') }}</a></li>
											<li><a class="dropdown-item" link-confirm="{{ trans('messages.disable_currencies_confirm') }}" href="{{ action('Admin\CurrencyController@disable') }}"><span class="material-symbols-rounded">
hide_source
</span> {{ trans('messages.disable') }}</a></li>
											<li>
												<a class="dropdown-item" link-confirm="{{ trans('messages.delete_currencies_confirm') }}" href="{{ action('Admin\CurrencyController@delete') }}">
													<span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}
												</a>
											</li>
										</ul>
									</div>
									<span class="filter-group">
										<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
										<select class="select" name="sort_order">
                                            <option value="currencies.created_at">{{ trans('messages.created_at') }}</option>
											<option value="currencies.name">{{ trans('messages.name') }}</option>
											<option value="currencies.code">{{ trans('messages.code') }}</option>
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
						@can('create', new Acelle\Model\Currency())
							<div class="text-end">
								<a href="{{ action("Admin\CurrencyController@create") }}" role="button" class="btn btn-secondary">
									<span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_currency') }}
								</a>
							</div>
						@endcan
					</div>

					<div class="pml-table-container">



					</div>
				</div>

				<script>
					var CurrenciesIndex = {
						getList: function() {
							return makeList({
								url: '{{ action('Admin\CurrencyController@listing') }}',
								container: $('.listing-form'),
								content: $('.pml-table-container')
							});
						}
					};
			
					$(document).ready(function() {
						CurrenciesIndex.getList().load();
					});
				</script>
@endsection
