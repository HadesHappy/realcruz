@if (count($results))
    <div href="{{ action('CampaignController@index') }}" class="search-head border-bottom d-block">
        <label class="fw-600">
            <span class="material-symbols-rounded me-1">
                manage_search 
                </span> {{ trans('messages.search.general') }}
        </label>
    </div>
    @foreach($results as $result)
        <a href="{{ $result['item']['url'] }}" class="search-result border-bottom d-block">
            <label class="fw-600">
                @foreach($result['item']['names'] as $name)
                    <span class="search-name">{{ $name }}</span>
                @endforeach
            </label>
            @if (isset($result['item']['desc']))
                <p class="desc text-muted mt-1 mb-0">{{ $result['item']['desc'] }}</p>
            @endif
        </a>
    @endforeach
@endif