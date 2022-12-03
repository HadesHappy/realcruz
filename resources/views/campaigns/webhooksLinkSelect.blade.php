@if (count($campaign->getLinks()))
    @include('helpers.form_control', [
        'type' => 'select',
        'name' => 'campaign_link_id',
        'value' => request()->campaign_link_id,
        'label' => trans('messages.webhook.select_campaign_link'),
        'options' => $campaign->getLinks()->map(function($link) {
            return ['text' => $link->url, 'value' => $link->id];
        })->toArray(),
        'rules' => ['type' => 'required'],
    ])
@else
    <div class="alert alert-danger">
        {{ trans('messages.campaigns.link_empty') }}
    </div>
@endif

