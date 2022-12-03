@if ($products->count() > 0)
	<table class="table table-box pml-table mt-2"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($products as $key => $product)
			@php
				$wooProduct = wc_get_product($product->ID);
			@endphp
			<tr>
				<td width="1%">
					<div class="product-image-list mr-3">
						{!! $wooProduct->get_image() !!}
					</div>
				</td>
				<td width="30%">
					<h5 class="no-margin text-normal">
						<span class="kq_search  fw-600" href="javascript:;">
							{{ $wooProduct->name }}
						</span>
					</h5>
					<span class="text-muted d-block mt-2">
						{{ trans('messages.created_at') }}:
						{{ wc_format_datetime($wooProduct->get_date_created()) }}
					</span>
				</td>
				<td class="small">
					<h5 class="no-margin stat-num">
						{!! $wooProduct->get_categories() !!}
					</h5>
					<span class="text-muted d-block mt-2">{{ trans('messages.product.categories') }}</span>
				</td>
				<td class="text-end">
					<a target="_blank" href="{{ get_permalink( $wooProduct->id ) }}" class="btn btn-light m-icon pl-3 copy-product-page-url">
						<span class="material-symbols-rounded me-2">
							inventory_2
							</span>Copy Product Page URL</a>

					@php
						$current_product_id = $wooProduct->id;
    
						$product = wc_get_product( $current_product_id );
						
						$checkout_url = wc_get_checkout_url().'?add-to-cart='.$current_product_id;

						
					@endphp
					<a target="_blank" href="{{ $checkout_url }}" class="btn btn-light m-icon pl-3 copy-product-checkout-url">
						<span class="material-symbols-rounded me-2">
							monetization_on
							</span>Copy checkout URL</a>
					<a href="{{ action('Site\ProductController@edit', $wooProduct->id) }}" class="btn btn-secondary m-icon pl-3">
						<span class="material-symbols-rounded me-2">
							edit
							</span>{{ trans('messages.edit') }}</a>
					<div class="btn-group">
						<button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
						<ul class="dropdown-menu dropdown-menu-end">
							<li>
								<a
									class="dropdown-item list-action-single"
									link-confirm="{{ trans('messages.product.delete.confirm', [
										'name' => $wooProduct->name,
									]) }}"
									link-method="POST"
									href="{{ action('Site\ProductController@delete', $wooProduct->id) }}">
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

	<script>
		$(function() {
			$('.copy-product-page-url').on('click', function(e) {
				e.preventDefault();

				var url = $(this).attr("href");

				copyToClipboard(url);

				new Dialog('alert', {
					message: 'Copied to clipboard!<br><br> You can add this URL to your own home or landing page, allowing your visitors to quickly BUY this product',
				});
			});

			$('.copy-product-checkout-url').on('click', function(e) {
				e.preventDefault();

				var url = $(this).attr("href");

				copyToClipboard(url);

				new Dialog('alert', {
					message: 'Copied to clipboard!<br><br> This is a simple PRODUCT PAGE. You can customize the PRODUCT PAGE template later on',
				});
			});
		});
	</script>

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
