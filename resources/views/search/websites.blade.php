@if ($websites->total())
    <a href="{{ action('WebsiteController@index', [
        'keyword' => request()->keyword
    ]) }}" class="search-head border-bottom d-block">
        <div class="d-flex">
            <div class="me-auto">
                <label class="fw-600">
                    <span class="material-symbols-rounded me-1">
                        public 
                        </span> {{ trans('messages.websites') }}
                </label>
            </div>
            <div>
                {{ $websites->count() }} / {{ $websites->total() }} · {{ trans('messages.search.view_all') }}
            </div>
        </div>
    </a>
    @foreach($websites as $website)
        <a href="{{ action('WebsiteController@show', [
            'uid' => $website->uid,
        ]) }}" class="search-result border-bottom d-block">
            <div class="d-flex align-items-center">
                <div>
                    <label class="fw-600">
                        {{ $website->title }}
                    </label>
                    <p class="desc text-muted mt-0 mb-0 text-nowrap">
                        {{ $website->url }}
                    </p>
                </div>
            </div>
        </a>
    @endforeach
@endif