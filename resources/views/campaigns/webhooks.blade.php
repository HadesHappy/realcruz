@extends('layouts.popup.large')

@section('title')
    {{ trans('messages.webhooks') }}
@endsection

@section('content')
    <div class="d-flex">
        <p>{{ trans('messages.webhooks.wording') }}</p>
        <div class="ms-4">
            <a href="javascript:;" class="btn btn-secondary add_webhooks_but text-nowrap">
                <i class="material-symbols-rounded">add</i>
                {{ trans('messages.webhooks.add') }}
            </a>
        </div>
    </div>

    <div class="webhooks_list">
        
    </div>

    <script>
        var CampaignsWebhooks = {
            addPopup: null,
			getAddPopup: function() {
				if (this.addPopup == null) {
					this.addPopup = new Popup({
						url: '{{ action('CampaignController@webhooksAdd', [
							'uid' => $campaign->uid,
						]) }}'
					});
				}

				return this.addPopup;
			},

            loadList: function() {
                var box = new Box($('.webhooks_list'), '{{ action('CampaignController@webhooksList', [
                    'uid' => $campaign->uid,
                ]) }}');
                box.load();
            }
        };

        $(function() {
            //
            CampaignsWebhooks.loadList();

            // click add webhook button
            $('.add_webhooks_but').on('click', function(e) {
				e.preventDefault();

				CampaignsWebhooks.getAddPopup().load();
			});
        })
    </script>
@endsection