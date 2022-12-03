<div class="row">
	<div class="col-md-12">
		<ul class="nav nav-tabs nav-tabs-top nav-underline">
			<li rel0="CampaignController/overview" class="nav-item">
				<a href="{{ action('CampaignController@overview', $campaign->uid) }}" class="nav-link">
					<span class="material-symbols-rounded">
auto_graph
</span> {{ trans('messages.overview') }}
				</a>
			</li>
			<li rel0="CampaignController/links" class="nav-item">
				<a href="{{ action('CampaignController@links', $campaign->uid) }}" class="nav-link">
					<span class="material-symbols-rounded">
link
</span> {{ trans('messages.links') }}
				</a>
			</li>
			<li rel0="CampaignController/openMap" class="nav-item">
				<a href="{{ action('CampaignController@openMap', $campaign->uid) }}" class="nav-link">
					<span class="material-symbols-rounded">
map
</span> {{ trans('messages.open_map') }}
				</a>
			</li>
			<li rel0="CampaignController/subscribers" class="nav-item">
				<a href="{{ action('CampaignController@subscribers', $campaign->uid) }}" class="nav-link">
					<span class="material-symbols-rounded">
people_outline
</span> {{ trans('messages.subscribers') }}
				</a>
			</li>
			<li class="nav-item"
				rel0="CampaignController/trackingLog"
				rel1="CampaignController/bounceLog"
				rel2="CampaignController/feedbackLog"
				rel3="CampaignController/openLog"
				rel4="CampaignController/clickLog"
				rel5="CampaignController/unsubscribeLog"
			>
				<a href="{{ action("AccountController@contact") }}" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
					<span class="material-symbols-rounded">
						article
</span> {{ trans('messages.sending_logs') }}
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li rel0="CampaignController/trackingLog">
						<a class="dropdown-item" href="{{ action('CampaignController@trackingLog', $campaign->uid) }}">
							<span class="material-symbols-rounded">
								article
</span> {{ trans('messages.tracking_log') }}
						</a>
					</li>
					<li rel0="CampaignController/bounceLog">
						<a class="dropdown-item" href="{{ action('CampaignController@bounceLog', $campaign->uid) }}">
							<span class="material-symbols-rounded">
								article
</span> {{ trans('messages.bounce_log') }}
						</a>
					</li>
					<li rel0="CampaignController/feedbackLog">
						<a class="dropdown-item" href="{{ action('CampaignController@feedbackLog', $campaign->uid) }}">
							<span class="material-symbols-rounded">
								article
</span> {{ trans('messages.feedback_log') }}
						</a>
					</li>
					<li rel0="CampaignController/openLog">
						<a class="dropdown-item" href="{{ action('CampaignController@openLog', $campaign->uid) }}">
							<span class="material-symbols-rounded">
								article
</span> {{ trans('messages.open_log') }}
						</a>
					</li>
					<li rel0="CampaignController/clickLog">
						<a class="dropdown-item" href="{{ action('CampaignController@clickLog', $campaign->uid) }}">
							<span class="material-symbols-rounded">
								article
</span> {{ trans('messages.click_log') }}
						</a>
					</li>
					<li rel0="CampaignController/unsubscribeLog">
						<a class="dropdown-item" href="{{ action('CampaignController@unsubscribeLog', $campaign->uid) }}">
							<span class="material-symbols-rounded">
								article
</span> {{ trans('messages.unsubscribe_log') }}
						</a>
					</li>
				</ul>
			</li>
			<li class="nav-item" rel0="CampaignController/templateReview">
				<a href="javascript:;" onclick="popupwindow('{{ action('CampaignController@preview', $campaign->uid) }}', `{{ $campaign->name }}`, 800)" class="nav-link">
					<span class="material-symbols-rounded">
auto_awesome_mosaic
</span> {{ trans('messages.email_review') }}
				</a>
			</li>
		</ul>
	</div>
</div>

<script>
	var downloaded = false;
	var downloadWindow;

	function goToDownLoad(logtype) {
		downloadWindow = window.open('{{ action('CampaignController@trackingLogDownload', ['uid' => $campaign->uid]) }}?logtype=' + logtype, '_blank');
	}

	function downloadAndCloseDownloadWindow(downloadUrl) {
		downloadWindow.close();
		window.location.href = downloadUrl;
	}
</script>
