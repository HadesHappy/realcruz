@extends('layouts.popup.large')

@section('title')
    <i class="material-symbols-rounded alert-icon mr-2">backup_table</i>
    {{ trans("messages.template") }}
@endsection

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h4 class="">{{ trans('messages.campaign.choose_your_template_layout') }}</h4>
			@include('campaigns.template._tabs')
			<div class="tab-content" id="pills-tabContent">
				<div class="">
					<div class="subsection pb-3">
						<h2 class="font-weight-semibold mb-0">
							@if (request()->category_uid)
								{{ Acelle\Model\TemplateCategory::findByUid(request()->category_uid)->name }}
							@elseif (request()->from == 'mine')
								{{ trans('messages.my_templates') }}
							@endif
						</h2>

						<div id="gallery" class="pb-4">
							<div class="listing-form">				
								<div class="row top-list-controls mt-0 py-2">
									<div class="col-md-9">
										<div class="filter-box">
											<span class="d-flex align-items-center mr-4">
												<input type="hidden" name="sort_order" value="id" />	
												<input type="hidden" name="sort_direction" value="desc" />	
												<input type="hidden" name="category_uid" value="{{ request()->category_uid }}" />
												<input type="hidden" name="from" value="{{ request()->from }}" />
												<input type="text" name="keyword" class="form-control search" value="{{ request()->keyword }}" placeholder="{{ trans('messages.type_to_search') }}" />
												<span class="material-symbols-rounded">
search
</span>
											</span>
										</div>
									</div>
								</div>
								
								<div id="galleryContent" class="pml-table-container">
								</div>
							</div>
						</div>
						<br style="clear:both" /><br style="clear:both" />
					</div>
				</div>
			</div>
        </div>
    </div>
        
    <script>
		var CampaignsTemplateLayout = {
			listUrl: '{{ action('CampaignController@templateLayoutList', [
				'uid' => $campaign->uid,
			]) }}',

			getList: function() {
				return makeList({
					url: this.listUrl,
					container: $('#gallery'),
					content: $('#galleryContent')
				});
			}
		}

		$(document).ready(function() {
			//load list
			CampaignsTemplateLayout.getList().load();

            $('a.choose-template-tab').click(function(e) {
				e.preventDefault();
			
				var url = $(this).attr('href');
			
				templatePopup.load(url);
			});
        });
		
		// legacy
        var builderSelectPopup = new Popup({
			onclose: function() {
				window.location = '{{ action('CampaignController@template', $campaign->uid) }}';
			}
		});
    </script>
@endsection