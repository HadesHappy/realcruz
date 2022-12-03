@if ($currencies->count() > 0)
	<table class="table table-box pml-table mt-2"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($currencies as $key => $item)
			<tr>
				<td width="1%">
					<div class="text-nowrap">
						<div class="checkbox inline me-1">
							<label>
								<input type="checkbox" class="node styled"
									name="uids[]"
									value="{{ $item->uid }}"
								/>
							</label>
						</div>
					</div>
				</td>
				<td>
					<h5 class="m-0 text-bold">
						<a class="kq_search d-block" href="{{ action('Admin\CurrencyController@edit', $item->uid) }}">{{ $item->name }}</a>
					</h5>
					<span class="text-muted">
						{{ trans('messages.updated_at') }}
						{{ Auth::user()->admin->formatDateTime($item->updated_at, 'date_full') }}
					</span>
				</td>
				<td class="stat-fix-size-sm">
					<div class="single-stat-box pull-left">
						<span class="no-margin stat-num kq_search">{{ $item->code }}</span>
						<br />
						<span class="text-muted">{{ trans("messages.code") }}</span>
					</div>
				</td>
				<td class="stat-fix-size-sm">
					<div class="single-stat-box pull-left">
						<span class="no-margin stat-num kq_search">{{ $item->format }}</span>
						<br />
						<span class="text-muted">{{ trans("messages.currency_format") }}</span>
					</div>
				</td>
				<td class="stat-fix-size">
					<span class="text-muted2 list-status pull-left">
						<span class="label label-flat bg-{{ $item->status }}">{{ $item->status }}</span>
					</span>
				</td>
				<td class="text-end text-nowrap pe-0" width="5%">
					@can('update', $item)
						<a href="{{ action('Admin\CurrencyController@edit', $item->uid) }}" data-popup="tooltip" title="{{ trans('messages.edit') }}" role="button" class="btn btn-secondary btn-icon"><span class="material-symbols-rounded">
edit
</span></a>
					@endcan
					@if (Auth::user()->can('delete', $item) || Auth::user()->can('enable', $item) || Auth::user()->can('disable', $item) || Auth::user()->can('delete', $item))
						<div class="btn-group">
							<button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
							<ul class="dropdown-menu dropdown-menu-end">
								@can('enable', $item)
									<li>
										<a class="dropdown-item list-action-single"
											link-confirm="{{ trans('messages.enable_admins_confirm') }}"
											href="{{ action('Admin\CurrencyController@enable', ["uids" => $item->uid]) }}">
											<span class="material-symbols-rounded">
play_arrow
</span> {{ trans('messages.enable') }}
										</a>
									</li>
								@endcan
								@can('disable', $item)
									<li>
										<a class="dropdown-item list-action-single"
										 link-confirm="{{ trans('messages.disable_admins_confirm') }}" href="{{ action('Admin\CurrencyController@disable', ["uids" => $item->uid]) }}">
											<span class="material-symbols-rounded">
hide_source
</span> {{ trans('messages.disable') }}
										</a>
									</li>
								@endcan
								<li>
									<a class="dropdown-item list-action-single" link-confirm="{{ trans('messages.delete_currencies_confirm') }}" href="{{ action('Admin\CurrencyController@delete', ['uids' => $item->uid]) }}">
										<span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}
									</a>
								</li>
							</ul>
						</div>
					@endcan
				</td>
			</tr>
		@endforeach
	</table>
	@include('elements/_per_page_select', ["items" => $currencies])
	
@elseif (!empty(request()->filters))
	<div class="empty-list">
		<i class="material-symbols-rounded">
assignment_turned_in
</i>
		<span class="line-1">
			{{ trans('messages.no_search_result') }}
		</span>
	</div>
@else
	<div class="empty-list">
		<span class="material-symbols-rounded">
paid
</span>
		<span class="line-1">
			{{ trans('messages.plan_empty_line_1') }}
		</span>
	</div>
@endif
