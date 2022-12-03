@extends('layouts.popup.large')

@section('title')
    {{ trans('messages.webhooks') }}
@endsection

@section('content')
    <div class="d-flex">
        <p>{{ trans('messages.webhooks.wording') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>{{ trans('messages.webhook.type') }}</th>
                <th>{{ trans('messages.webhook.description') }}</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($campaign->campaignWebhooks()->open()->get() as $webhook)
                <tr>
                    <td width="5%">{{ trans('messages.webhook.type.' . $webhook->type . '.short') }}</td>
                    <td>
                        <p class="mb-0">{{ trans('messages.webhook.type.' . $webhook->type . '.desc') }}</p>
                        @if ($webhook->type == 'click')
                            <p class="mb-0 text-muted2">
                                <span class="material-symbols-rounded text-muted2 me-1 xtooltip" title="{{ trans('messages.link') }}">ads_click</span>
                                <a target="_blank" href="{{ $webhook->campaignLink->url }}">{{ $webhook->campaignLink->url }}</a>
                            </p>
                        @endif
                        <p class="mb-0 text-muted2">
                            <span class="material-symbols-rounded text-muted2 me-1 xtooltip" title="{{ trans('messages.webhook.endpoint') }}">gps_fixed</span> {{ $webhook->endpoint }}
                        </p>
                    </td>
                    <td class="text-nowrap text-end">
                        <div class="test-result">

                        </div>
                    </td>
                    <td class="text-nowrap text-end">
                        <a href="{{ action('CampaignController@webhooksTestMessage', [
                            'webhook_uid' => $webhook->uid,
                            'message_id' => request()->message_id,
                        ]) }}" class="btn btn-light test-webhook">
                            <i class="material-symbols-rounded">play_arrow</i>
                            {{ trans('messages.webhook.test') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

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

            testWebhook: function(url, callback) {

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                    }
                }).done(function(response) {
                    if (typeof(callback) != 'undefined') {
                        callback(response);
                    }
                }).always(function() {
                    
                });
            }
        };

        $(function() {
            // click add webhook button
            $('.add_webhooks_but').on('click', function(e) {
				e.preventDefault();

				CampaignsWebhooks.getAddPopup().load();
			});
            
            // click test button
            $('.test-webhook').on('click', function(e) {
				e.preventDefault();
                var but = $(this);
                var url = but.attr('href');
                var resultBox = but.closest('tr').find('.test-result');

                addButtonMask(but);
                resultBox.html("");

				CampaignsWebhooks.testWebhook(url, function(result) {
                    resultBox.html(result);

                    removeButtonMask(but);
                });
			});
        })
    </script>
@endsection