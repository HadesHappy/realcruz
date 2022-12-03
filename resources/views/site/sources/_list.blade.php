@if ($sources->count() > 0)
	<table class="table table-box pml-table mt-2"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($sources as $key => $source)
			<tr>
				<td width="1%">
					<div class="product-image-list mr-3">
						<img src="{{ url('images/' . $source->type . '_list.png') }}" />
					</div>
				</td>
				<td>
					<a class="kq_search fw-600 d-block list-title" href="{{ action('Site\SourceController@show', $source->uid) }}">
						{{ $source->getName() }}
					</a>
					<span class="text-muted d-block mt-1">
						{{ trans('messages.created_at') }}:
						{{ Auth::user()->customer->formatDateTime($source->created_at, 'date_full') }}
					</span>
				</td>				
				<td>
					<h5 class="no-margin stat-num">
						{{ number_with_delimiter($source->productsCount(), $precision = 0) }}
					</h5>
					<span class="text-muted d-block mt-2">{{ trans('messages.products') }}</span>
				</td>
				<td>
					<h5 class="no-margin stat-num">
						{{ Auth::user()->customer->formatDateTime($source->updated_at, 'date_full') }}
					</h5>
					<span class="text-muted d-block mt-2">{{ trans('messages.source.last_sync_at') }}</span>
				</td>
				<td class="text-end">
					<a href="{{ action('Site\SourceController@sync', $source->uid) }}"
						link-method="POST"
						role="button" class="btn btn-secondary m-icon sync-button">
						<span class="material-symbols-rounded">
							sync
							</span> {{ trans('messages.source.sync') }}</a>
					<div class="btn-group">
						<button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
						<ul class="dropdown-menu dropdown-menu-end">
							<li>
								<a
									class="dropdown-item list-action-single"
									link-confirm="{{ trans('messages.source.delete.confirm') }}"
									link-method="POST"
									href="{{ action('Site\SourceController@delete', ['uids' => $source->uid]) }}">
									<span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}
								</a>
							</li>
						</ul>
					</div>
				</td>
			</tr>
		@endforeach
	</table>
	@include('elements/_per_page_select', ["items" => $sources])

	<script>
		$(function() {
			$('.sync-button').on('click', function() {
				addMaskLoading('{{ trans('messages.source.importing_product') }}');
			});
		});
	</script>
		
@elseif (!empty(request()->keyword))
	<div class="empty-list">
		<span class="material-symbols-rounded">
			legend_toggle
			</span>
		<span class="line-1">
			{{ trans('messages.source.not_found') }}
		</span>
	</div>
@else
	<div class="empty-list">
		<span class="material-symbols-rounded">
			legend_toggle
			</span>
		<span class="line-1">
			{{ trans('messages.source.empty') }}
		</span>
		<span class="line-2">
			{{ trans('messages.list_empty_line_2') }}
		</span>
	</div>
@endif
