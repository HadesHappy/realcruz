@if ($campaigns->count() > 0)
	<table class="table table-box pml-table mt-2"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($campaigns as $key => $campaign)
			<tr>
				<td width="1%">
					<div class="text-nowrap">
						<div class="checkbox inline me-1">
							<label>
								<input type="checkbox" class="node styled"
									name="uids[]"
									value="{{ $campaign->uid }}"
								/>
							</label>
						</div>
					</div>
				</td>
				<td>
					<a class="kq_search fw-600 d-block list-title" href="{{ action('CampaignController@show', $campaign->uid) }}">
						{{ $campaign->name }}
					</a>
					<span class="text-muted">{{ trans('messages.' . $campaign->type) }}</span>

					@if ($campaign->readCache('SubscriberCount'))
						<div>
							<span class="text-semibold" data-popup="tooltip" title="{{ $campaign->displayRecipients() }}">
								{{ number_with_delimiter($campaign->readCache('SubscriberCount')) }} {{ trans('messages.recipients') }}
							</span>
						</div>
					@endif

					@if ($campaign->status != 'new' && isset($campaign->run_at))
						<span class="text-muted2 d-block">{{ trans('messages.run_at') }}: <span class="material-symbols-rounded">
							alarm
							</span>
							 {{ isset($campaign->run_at) ? Auth::user()->customer->formatDateTime($campaign->run_at, 'date_full') : "" }}</span>
					@else
						<span class="text-muted2 d-block">{{ trans('messages.updated_at') }}: {{ Auth::user()->customer->formatDateTime($campaign->created_at, 'date_full') }}</span>
					@endif
				</td>
				@if ($campaign->status != 'new')
					<td class="stat-fix-size-sm">
						<div class="single-stat-box pull-left ml-20">
							<span class="no-margin text-primary stat-num">{{ $campaign->isSending() ? number_to_percentage($campaign->deliveredRate(true)) : number_to_percentage($campaign->readCache('DeliveredRate')) }}</span>
							<div class="progress progress-xxs">
								<div class="progress-bar progress-bar-info" style="width: {{ $campaign->isSending() ? number_to_percentage($campaign->deliveredRate(true)) : number_to_percentage($campaign->readCache('DeliveredRate')) }}">
								</div>
							</div>
							<span class="text-semibold text-nowrap">{{ $campaign->isSending() ? number_with_delimiter($campaign->deliveredCount()) : number_with_delimiter($campaign->readCache('DeliveredCount', 0)) }} / {{ number_with_delimiter($campaign->readCache('SubscriberCount', 0))  }}</span>
							<br />
							<span class="text-muted">{{ trans('messages.sent') }}</span>
						</div>
					</td>
					<td class="stat-fix-size-sm">
						<div class="single-stat-box pull-left ml-20">
							<span class="no-margin text-primary stat-num">{{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}</span>
							<div class="progress progress-xxs">
								<div class="progress-bar progress-bar-info" style="width: {{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}">
								</div>
							</div>
							<span class="text-muted">{{ trans('messages.open_rate') }}</span>
						</div>
					</td>
					<td class="stat-fix-size-sm">
						<div class="single-stat-box pull-left ml-20">
							<span class="no-margin text-primary stat-num">{{ number_to_percentage($campaign->readCache('ClickedRate')) }}</span>
							<div class="progress progress-xxs">
								<div class="progress-bar progress-bar-info" style="width: {{ number_to_percentage($campaign->readCache('ClickedRate')) }}">
								</div>
							</div>
							<span class="text-muted">{{ trans('messages.click_rate') }}</span>
						</div>
					</td>
				@else
					<td></td>
					<td></td>
					<td></td>
				@endif
				<td width="15%" class="text-center">
					<span class="text-muted2 list-status pull-left" title='{{ $campaign->isError() ? $campaign->extractErrorMessage() : '' }}' data-popup='tooltip'>
						<span class="label label-flat bg-{{ $campaign->status }}">{{ trans('messages.campaign_status_' . $campaign->status) }}</span>
					</span>
					<pre style="display:none">{{ $campaign->last_error }}</pre>
				</td>
				<td class="text-end text-nowrap pe-0">
					<div class="d-flex align-items-center text-nowrap justify-content-end">
						@if (\Gate::allows('update', $campaign))
							<a href="{{ action('CampaignController@edit', $campaign->uid) }}" role="button"
								class="btn btn-secondary btn-icon ms-1"> <span class="material-symbols-rounded">
	edit
	</span> {{ trans('messages.edit') }}</a>
						@endif
						@if (\Gate::allows('overview', $campaign))
							<a href="{{ action('CampaignController@overview', $campaign->uid) }}" data-popup="tooltip"
								title="{{ trans('messages.overview') }}" role="button"
								class="btn btn-primary btn-icon ms-1"
							>
								<i class="icon-stats-growth"></i> {{ trans('messages.campaign.overview_statistics') }}</a>
						@endif
						@if (\Gate::allows('delete', $campaign) || \Gate::allows('pause', $campaign) || \Gate::allows('restart', $campaign))
							<div class="btn-group ms-1" role="group">
								<button id="btnGroupDrop1" role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
								<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="btnGroupDrop1">
									@if (\Gate::allows('resend', $campaign))
										<li>
											<a class="dropdown-item resend-campaign"
											href="{{ action('CampaignController@resend', ["uid" => $campaign->uid]) }}">
											<span class="material-symbols-rounded me-2">
	restart_alt
	</span> {{ trans("messages.campaign.resend") }}</a></li>
									@endif
									@if (\Gate::allows('send_test_email', $campaign))
										<li><a class="dropdown-item send-a-test-email-link" href="{{ action('CampaignController@sendTestEmail', [
											'uid' => $campaign->uid,
										]) }}">
											<span class="material-symbols-rounded me-2">
forward_to_inbox
</span> {{ trans("messages.send_a_test_email") }}</a></li>
									@endif
									@if (\Gate::allows('pause', $campaign))
										<li><a class="dropdown-item list-action-single"
											link-method="POST"
											link-confirm="{{ trans('messages.pause_campaigns_confirm', ['number' => '1']) }}"
											href="{{ action('CampaignController@pause', ["uids" => $campaign->uid]) }}">
											<span class="material-symbols-rounded me-2">
	motion_photos_pause
	</span>{{ trans("messages.pause") }}</a></li>
									@endif
									@if (\Gate::allows('restart', $campaign))
										<li><a class="dropdown-item list-action-single"
											link-method="POST"
											link-confirm="{{ trans('messages.restart_campaigns_confirm', ['number' => '1']) }}" href="{{ action('CampaignController@restart', ["uids" => $campaign->uid]) }}">
											<span class="material-symbols-rounded me-2">
	restore
	</span> {{ trans("messages.restart") }}</a></li>
									@endif
									@if (\Gate::allows('copy', $campaign))
										<li>
											<a class="copy-campaign-button dropdown-item"
												href="{{ action('CampaignController@copy', [
													'uid' => $campaign->uid,
													'copy_campaign_uid' => $campaign->uid,
												]) }}"
											>
												<span class="material-symbols-rounded me-2">
													copy_all
												</span> {{ trans('messages.copy') }}
											</a>
										</li>
									@endif
									@if (\Gate::allows('delete', $campaign))
										<li><a
											class="dropdown-item list-action-single"
											link-method="POST"
											link-confirm="{{ trans('messages.delete_campaign_confirm', ['name' => $campaign->name]) }}"
											href="{{ action('CampaignController@delete', ["uids" => $campaign->uid]) }}">
											<span class="material-symbols-rounded me-2">
	delete_outline
	</span> {{ trans("messages.delete") }}</a></li>
									@endif
								</ul>
							</div>
						@endif
					</div>
				</td>
			</tr>
		@endforeach
	</table>
	@include('elements/_per_page_select', ["items" => $campaigns])
	

	<script>
		var CampaignsList = {
			copyPopup: null,

			getCopyPopup: function() {
				if (this.copyPopup === null) {
					this.copyPopup = new Popup();
				}

				return this.copyPopup;
			}
		}

		var CampaignsResendPopup = {
            popup: null,

            load: function(url) {
                if (this.popup == null) {
                    this.popup = new Popup({
                        url: url
                    });
                }
                this.popup.load({
					url: url
				});
            }
        }

		var CampaignsSendTestEmailPopup = {
            popup: null,

            load: function(url) {
                if (this.popup == null) {
                    this.popup = new Popup({
                        url: url
                    });
                }
                this.popup.load({
					url: url
				});
            }
        }

		$('.resend-campaign').click(function(e) {
			e.preventDefault();

			var url = $(this).attr('href');

			CampaignsResendPopup.load(url);
		});

		$('.copy-campaign-button').on('click', function(e) {
			e.preventDefault();			
			var url = $(this).attr('href');

			CampaignsList.getCopyPopup().load({
				url: url
			});
		});

		$('.send-a-test-email-link').on('click', function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			
			CampaignsSendTestEmailPopup.load(url);
		});
	</script>
@elseif (!empty(request()->keyword))
	<div class="empty-list">
		<span class="material-symbols-rounded">
			auto_awesome
			</span>
		<span class="line-1">
			{{ trans('messages.no_search_result') }}
		</span>
	</div>
@else
	<div class="empty-list">
		<span class="material-symbols-rounded">
			auto_awesome
			</span>
		<span class="line-1">
			{{ trans('messages.campaign_empty_line_1') }}
		</span>
	</div>
@endif
