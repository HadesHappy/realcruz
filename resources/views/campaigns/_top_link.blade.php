@if ($campaign->getTopLinks()->count())
    <h3 class="text-semibold"><span class="material-symbols-rounded">
link
</span> {{ trans('messages.top_links_clicked') }}</h3>
    
    <div class="stat-table">
        @foreach ($campaign->getTopLinks()->get() as $link)
            <div class="stat-row">
                <p class="text-muted">
                    <a target="_blank" href="{{ $link->url }}">{{ $link->url }}</a>
                </p>
                <span class="pull-right num">
                    {{ $link->aggregate }}
                </span>
            </div>
        @endforeach
    </div>

    <div class="text-end">
        <a href="{{ action('CampaignController@clickLog', $campaign->uid) }}" class="btn btn-info bg-teal-600">{{ trans('messages.click_log') }} <span class="material-symbols-rounded">
arrow_forward
</span></a>
    </div>
@endif