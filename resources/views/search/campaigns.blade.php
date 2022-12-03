@if ($campaigns->total())
    <a href="{{ action('CampaignController@index', [
        'keyword' => request()->keyword
    ]) }}" class="search-head border-bottom d-block">
        <div class="d-flex">
            <div class="me-auto">
                <label class="fw-600">
                    <span class="material-symbols-rounded me-1">
                        dynamic_feed 
                        </span> {{ trans('messages.campaigns') }}
                </label>
            </div>
            <div>
                {{ $campaigns->count() }} / {{ $campaigns->total() }} · {{ trans('messages.search.view_all') }}
            </div>
        </div>
    </a>
    @foreach($campaigns as $campaign)
        <a href="{{ action('CampaignController@show', $campaign->uid) }}" class="search-result border-bottom d-block">
            <div class="d-flex align-items-center">
                <div>
                    <label class="fw-600">
                        {{ $campaign->name }}
                    </label>
                    <p class="desc text-muted mt-1 mb-0">
                        @if ($campaign->status != 'new')
                        
                            <span class="fw-600">{{ $campaign->isSending() ? number_with_delimiter($campaign->deliveredCount()) : number_with_delimiter($campaign->readCache('DeliveredCount', 0)) }}</span>
                            /
                            <span class="fw-600">{{ number_with_delimiter($campaign->readCache('SubscriberCount', 0))  }}</span>
                            ·  
                            <span class="fw-600"> {{ number_to_percentage($campaign->readCache('UniqOpenRate')) }} </span> {{ trans('messages.open_rate') }}
                            ·  
                            <span class="fw-600">{{ number_to_percentage($campaign->readCache('ClickedRate')) }}</span> {{ trans('messages.click_rate') }}
                        @else
                            {{ number_with_delimiter($campaign->readCache('SubscriberCount')) }} {{ trans('messages.recipients') }}
                        @endif
                    </p>
                </div>
            </div>
        </a>
    @endforeach
@endif