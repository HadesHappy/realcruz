@if ($subscribers->total())
    <a href="{{ action('MailListController@index') }}" class="search-head border-bottom d-block">
        <div class="d-flex">
            <div class="me-auto">
                <label class="fw-600">
                    <span class="material-symbols-rounded me-1">
                        people 
                        </span> {{ trans('messages.subscribers') }}
                </label>
            </div>
            <div>
                {{ $subscribers->count() }} / {{ number_with_delimiter($subscribers->total(), $precision = 0) }} · {{ trans('messages.search.go_to_lists') }}
            </div>
        </div>
    </a>
    @foreach($subscribers as $subscriber)
        <a href="{{ action('SubscriberController@edit', ['list_uid' => $subscriber->mailList->uid ,'uid' => $subscriber->uid]) }}" class="search-result border-bottom d-block">
            <div class="d-flex align-items-center">
                <div>
                    <img width="40px" height="40px" class="shadow-sm me-3 rounded-circle" src="{{ (isSiteDemo() ? 'https://i.pravatar.cc/300?v=' . $subscriber->uid : action('SubscriberController@avatar',  $subscriber->uid)) }}" />
                </div>
                <div>
                    <div class="d-flex">
                        <label class="fw-600">
                            {{ $subscriber->email }}
                        </label>
                    </div>
                        
                    <p class="desc text-muted mb-0">
                        <span>
                            @if ($subscriber->getFullName())
                                <span class="me-2">{{ $subscriber->getFullName() }}</span>
                            @endif
                            <span class="label label-flat label-sm bg-{{ $subscriber->status }}">{{ trans('messages.' . $subscriber->status) }}</span></span>
                        <span class="text-truncate d-block mb-1" style="max-width:300px;overflow:hidden;">
                            <span class="material-symbols-rounded">
                                fact_check
                                </span> <span>{{ $subscriber->mailList->name }}</span>
                        </span>
                    </p>
                </div>
            </div>
        </a>
    @endforeach
@endif