@if ($lists->total())
    <a href="{{ action('MailListController@index', [
        'keyword' => request()->keyword
    ]) }}" class="search-head border-bottom d-block">
        <div class="d-flex">
            <div class="me-auto">
                <label class="fw-600">
                    <span class="material-symbols-rounded me-1">
                        fact_check 
                        </span> {{ trans('messages.lists') }}
                </label>
            </div>
            <div>
                {{ $lists->count() }} / {{ $lists->total() }} · {{ trans('messages.search.view_all') }}
            </div>
        </div>
    </a>
    @foreach($lists as $list)
        <a href="{{ action('MailListController@overview', ['uid' => $list->uid]) }}" class="search-result border-bottom d-block">
            <div class="d-flex align-items-center">
                <div>
                    <label class="fw-600 text-nowrap">
                        {{ $list->name }}
                    </label>
                    <p class="desc text-muted mt-1 mb-0 text-nowrap">
                        <span class="fw-600">{{ number_with_delimiter($list->readCache('SubscriberCount', 0)) }}</span>
                        {{ trans("messages." . Acelle\Library\Tool::getPluralPrase('subscriber', $list->readCache('SubscriberCount', 0))) }} ·  
                        <span class="fw-600"> {{ $list->openUniqRate() }}% </span> {{ trans('messages.open_rate') }} · 
                        <span class="fw-600">{{ $list->readCache('ClickedRate', 0) }}% </span>{{ trans('messages.click_rate') }}</p>
                </div>
            </div>
                
        </a>
    @endforeach
@endif