@if ($products->count() > 0)
	<table class="table table-box pml-table mt-2"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($products as $key => $product)
			<tr>
				<td width="1%">
					<div class="product-image-list mr-3">
						<img src="{{ $product->getImageUrl() }}" />
					</div>
				</td>
				<td width="50%">
					<h5 class="no-margin text-normal">
						<span class="kq_search" href="javascript:;">
							{{ $product->title }}
						</span>
					</h5>
					<span class="text-muted d-block mt-2">
						{{ trans('messages.created_at') }}:
						{{ Auth::user()->customer->formatDateTime($product->created_at, 'date_full') }}
					</span>
				</td>
				<td>
					<h5 class="no-margin stat-num">
						{{ $product->source->getName() }}
					</h5>
					<span class="text-muted d-block mt-2">{{ trans('messages.source') }}</span>
				</td>
				<td class="text-end">
					<a href="{{ action('SourceController@sync', $product->uid) }}"
						link-method="POST"
						role="button" class="btn btn-secondary m-icon pl-3">
						<span class="material-symbols-rounded me-2">
							link
							</span>{{ trans('messages.view') }}</a>
					<div class="btn-group">
						<button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
						<ul class="dropdown-menu dropdown-menu-end">
							<li>
								<a
									class="dropdown-item list-action-single"
									link-confirm="{{ trans('messages.source.delete.confirm') }}"
									link-method="POST"
									href="{{ action('SourceController@delete', ['uids' => $product->uid]) }}">
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
	@include('elements/_per_page_select', ["items" => $products])
		
@elseif (!empty(request()->keyword))
	<div class="empty-list">
		<span class="material-symbols-rounded">
			category
			</span>
		<span class="line-1">
			{{ trans('messages.no_search_result') }}
		</span>
	</div>
@else
	<div class="empty-list">
		<span class="material-symbols-rounded">
			category
			</span>
		<span class="line-1 text-muted">
			<p>{!! trans('messages.product.no_product') !!}</p>
		</span>
	</div>
@endif
