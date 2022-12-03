@if($campaign->campaignWebhooks()->count())
    <table class="table">
        <thead>
            <tr>
                <th>{{ trans('messages.webhook.type') }}</th>
                <th>{{ trans('messages.webhook.description') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($campaign->campaignWebhooks as $webhook)
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
                        <a href="{{ action('CampaignController@webhooksSampleRequest', [
                            'webhook_uid' => $webhook->uid,
                        ]) }}" class="btn btn-light sample-request-webhook">
                            <i class="material-symbols-rounded me-1">ios_share</i>
                            {{ trans('messages.webhook.sample_request') }}
                        </a>
                        <a href="{{ action('CampaignController@webhooksTest', [
                            'webhook_uid' => $webhook->uid,
                        ]) }}" class="btn btn-light test-webhook">
                            <i class="material-symbols-rounded">bug_report</i>
                            {{ trans('messages.webhook.test') }}
                        </a>
                        <div class="btn-group">
                            <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item edit-webhook"
                                        href="{{ action('CampaignController@webhooksEdit', [
                                            'webhook_uid' => $webhook->uid,
                                        ]) }}">
                                        <i class="material-symbols-rounded">edit</i>
                                        {{ trans('messages.edit') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item delete-webhook"
                                        href="{{ action('CampaignController@webhooksDelete', [
                                            'webhook_uid' => $webhook->uid,
                                        ]) }}">
                                        <i class="material-symbols-rounded">delete</i>
                                        {{ trans('messages.delete') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
<div class="empty-list">
    <span class="material-symbols-rounded">
        cable
</span>
    <span class="line-1">
        {{ trans('messages.webhooks.empty') }}
    </span>
</div>
@endif

<script>
    var CampaignsWebhooksList = {
        editPopup: null,
        getEditPopup: function(url) {
            if (this.editPopup == null) {
                this.editPopup = new Popup();
            }

            return this.editPopup;
        },

        sampleRequestPopup: null,
        getSampleRequestPopup: function(url) {
            if (this.sampleRequestPopup == null) {
                this.sampleRequestPopup = new Popup();
            }

            return this.sampleRequestPopup;
        },

        testPopup: null,
        getTestPopup: function(url) {
            if (this.testPopup == null) {
                this.testPopup = new Popup();
            }

            return this.testPopup;
        },

        delete: function(url) {
            new Dialog('confirm', {
                message: '{{ trans('messages.webhook.delete.confirm') }}',
                ok: function() {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: CSRF_TOKEN
                        },
                        success: function (response) {
                            notify({
                                type: 'success',
                                message: response.message
                            });

                            CampaignsWebhooks.loadList();
                        }
                    });
                }
            });
        }
    }

    $(function() {
        
        $('.edit-webhook').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            CampaignsWebhooksList.getEditPopup().load(url);
        });

        $('.delete-webhook').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            CampaignsWebhooksList.delete(url);
        });

        $('.sample-request-webhook').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            CampaignsWebhooksList.getSampleRequestPopup().load(url);
        });

        $('.test-webhook').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            CampaignsWebhooksList.getTestPopup().load(url);
        });
    });
</script>