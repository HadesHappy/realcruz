<br />
<div class="row">
    <div class="col-md-6">
        <div class="content-group-sm">
            <h4 class="text-semibold"><span class="material-symbols-rounded">
people
</span> {{ trans('messages.subscribers_most_open') }}</h4>                        
        </div>
        @if ($campaign->uniqueOpenCount())
            <div class="stat-table">
                @foreach ($campaign->getTopOpenSubscribers()->get() as $subscriber)
                    <div class="stat-row">
                        <p class="fw-600"><span class="material-symbols-rounded me-1">
                            alternate_email
                            </span> {{ $subscriber->email }}</p>
                        <span class="pull-right num">
                            {{ number_with_delimiter($subscriber->count, $precision = 0) }}
                        </span>
                    </div>
                @endforeach
            </div>
            <div class="text-end">
                <a href="{{ action('CampaignController@openLog', $campaign->uid) }}" class="btn btn-info bg-teal-600">{{ trans('messages.open_log') }} <span class="material-symbols-rounded">
arrow_forward
</span></a>
            </div>
        @else
            <div class="empty-chart-pie">
                <div class="empty-list">
                    <span class="material-symbols-rounded">
auto_awesome
</span>
                    <span class="line-1">
                        {{ trans('messages.log_empty_line_1') }}
                    </span>
                </div>
            </div>
        @endif

    </div>
    <div class="col-md-6">
        <div class="content-group-sm">
            <h4 class="text-semibold"><i class="icon-location4"></i> {{ trans('messages.top_location_by_opens') }}</h4>
        </div>
            
        @if ($campaign->uniqueOpenCount())
            <div class="stat-table">
                @foreach ($campaign->topLocations()->get() as $location)
                    <div class="stat-row">
                        <p class="fw-600"><span class="material-symbols-rounded me-1">
                            place
                            </span> <span class="fw-600">{{ $location->name() }}</span> - {{ $location->ip_address }}</p>
                        <span class="pull-right num">
                            {{ number_with_delimiter($location->aggregate, $precision = 0) }}
                        </span>
                    </div>
                @endforeach 
            </div>
            <div class="text-end">
                <a href="{{ action('CampaignController@openMap', $campaign->uid) }}" class="btn btn-info bg-teal-600">{{ trans('messages.open_map') }} <span class="material-symbols-rounded">
arrow_forward
</span></a>
            </div>
        @else
            <div class="empty-chart-pie">
                <div class="empty-list">
                    <span class="material-symbols-rounded">
auto_awesome
</span>
                    <span class="line-1">
                        {{ trans('messages.log_empty_line_1') }}
                    </span>
                </div>
            </div>
        @endif

    </div>
</div>