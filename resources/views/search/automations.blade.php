@if ($automations->total())
    <a href="{{ action('Automation2Controller@index', [
        'keyword' => request()->keyword
    ]) }}" class="search-head border-bottom d-block">
        <div class="d-flex">
            <div class="me-auto">
                <label class="fw-600">
                    <span class="material-symbols-rounded me-1">
                        account_tree 
                        </span> {{ trans('messages.automations') }}
                </label>
            </div>
            <div>
                {{ $automations->count() }} / {{ number_with_delimiter($automations->total(), $precision = 0) }} · {{ trans('messages.search.view_all') }}
            </div>
        </div>
    </a>
    @foreach($automations as $automation)
        <a href="{{ action('Automation2Controller@edit', $automation->uid) }}" class="search-result border-bottom d-block">
            <div class="d-flex align-items-center">
                <div>
                    <label class="fw-600">
                        {{ $automation->name }}
                    </label>
                    <p class="desc text-muted mt-1 mb-0">
                        <span class="fw-600">{{ $automation->mailList->readCache('SubscriberCount', '#') }}</span>
                        {{ trans('messages.automation.overview.contacts') }}
                        ·  
                        <span class="fw-600">{{ $automation->countEmails() }}</span>
                        {{ trans('messages.emails') }}
                        ·  
                        <span class="fw-600">{{ $automation->readCache('SummaryStats') ? number_to_percentage($automation->readCache('SummaryStats')['complete']) : '#' }}</span>
                        {{ trans('messages.complete') }}
                    </p>
                </div>
            </div>
        </a>
    @endforeach
@endif