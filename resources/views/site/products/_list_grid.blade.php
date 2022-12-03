@if ($products->count() > 0)
	<div class="row mt-4">
		@foreach ($products as $key => $product)
			@php
				$wooProduct = wc_get_product($product->ID);
			@endphp
			<div class="col-md-2 col-sm-6 mb-4">
				<div class="card mb-4 shadow-sm">
					<span class="product-image-box woo-image border-bottom">
						{!! $wooProduct->get_image() !!}
					</span>
					<div class="card-body p-3">
						<h5 title="{{ $product->title }}" class="fw-600 mt-1 mb-2 text-ellipsis">{{ $wooProduct->name }}</h5>
						<p style="display: block;
						height: 49px;
						overflow: hidden;" class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
						<div class="">
							<div class="d-flex align-items-center">
								

								@php
									$current_product_id = $wooProduct->id;
				
									$product = wc_get_product( $current_product_id );
									
									$checkout_url = wc_get_checkout_url().'?add-to-cart='.$current_product_id;
			
									
								@endphp
								<a target="_blank" href="{{ $checkout_url }}" class="btn btn-light m-icon px-2 me-1 text-center copy-product-checkout-url xtooltip" title="Copy checkout URL">
									<span class="material-symbols-rounded">
										monetization_on
										</span></a>

								<a target="_blank" href="{{ get_permalink( $wooProduct->id ) }}" class="btn btn-light m-icon px-2 me-1 text-center copy-product-page-url xtooltip" title="Copy Product Page URL">
									<span class="material-symbols-rounded">
										inventory_2
										</span></a>
								<a href="{{ action('Site\ProductController@edit', $wooProduct->id) }}" role="button" class="btn btn-secondary px-2">
									<span class="material-symbols-rounded">
										edit
										</span>
								</a>
								
								<a
									class="btn btn-link list-action-single"
									link-confirm="{{ trans('messages.product.delete.confirm', [
										'name' => $wooProduct->name,
									]) }}"
									link-method="POST"
									href="{{ action('Site\ProductController@delete', $wooProduct->id) }}">
									{{ trans('messages.delete') }}
								</a>
								<span class="text-muted ml-auto text-primary m-icon d-flex align-items-center">
									{{-- <img width="20px" class="mr-2 list-source-img" src="{{ url('images/woo_list.png') }}" /> --}}
									<span class="material-symbols-rounded">
										qr_code
										</span>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	</div>
	
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
