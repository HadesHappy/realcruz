<h2 class="mt-4 mb-4">
    <span class="text-teal text-bold">{{ $campaign->readCache('SubscriberCount', 0) }}</span>
    {{ trans('messages.' . \Acelle\Library\Tool::getPluralPrase('recipient', $campaign->readCache('SubscriberCount', 0))) }}
</h2>

<div class="row fs-7">
    <div class="col-md-6 campaigns-summary">
        <div class="mb-2">
            <span class="text-bold d-inline-block text-end pe-3" style="width:120px">
                <span class="label bg-light">{{ trans('messages.from') }} <span class="material-symbols-rounded">
                    alternate_email
                    </span></span>
            </span>
            @if (is_object($campaign->defaultMailList))
                <a href="{{ action('MailListController@overview', ['uid' => $campaign->defaultMailList->uid]) }}">
                    {!! $campaign->displayRecipients() !!}
                </a>
            @else
                {!! $campaign->displayRecipients() !!}
            @endif
        </div>
        <div class="mb-2">
            <span class="text-bold d-inline-block text-end pe-3" style="width:120px">
                <span class="label bg-light">{{ trans('messages.subject') }} <span class="material-symbols-rounded">
                    subject
                    </span></span></span>
            {{ $campaign->subject }}
        </div>
        <div class="mb-2">
            <span class="text-bold d-inline-block text-end pe-3" style="width:120px">
                <span class="label bg-light">{{ trans('messages.from_email') }} <span class="material-symbols-rounded">
                    alternate_email
                    </span></span></span>
                    <a href="mailto:{{ $campaign->from_email }}">{{ $campaign->from_email }}</a>
        </div>
        <div class="mb-2">
            <span class="text-bold d-inline-block text-end pe-3" style="width:120px">
                <span class="label bg-light">{{ trans('messages.from_name') }} <span class="material-symbols-rounded">
                    subject
                    </span></span></span>
            {{ $campaign->from_name }}
        </div>

    </div>
    <div class="col-md-6">
        <div class="mb-2">
            <span class="text-bold d-inline-block text-end pe-3" style="width:120px">
                <span class="label bg-light">{{ trans('messages.reply_to') }} <span class="material-symbols-rounded">
                    alternate_email
                    </span></span></span>
            <a href="mailto:{{ $campaign->reply_to }}">{{ $campaign->reply_to }}</a>
        </div>
        <div class="mb-2">
            <span class="text-bold d-inline-block text-end pe-3" style="width:120px">
                <span class="label bg-light">{{ trans('messages.updated_at') }} <span class="material-symbols-rounded">
                    event
                    </span></span></span>
            {{ Auth::user()->customer->formatDateTime($campaign->updated_at, 'date_full') }}
        </div>
        <div class="mb-2">
            <span class="text-bold d-inline-block text-end pe-3" style="width:120px">
                <span class="label bg-light">{{ trans('messages.run_at') }} <span class="material-symbols-rounded">
                    event
                    </span></span></span>
            {{ isset($campaign->run_at) ? Auth::user()->customer->formatDateTime($campaign->run_at, 'date_full') : "" }}
        </div>
        <div class="mb-2">
            <span class="text-bold d-inline-block text-end pe-3" style="width:120px">
                <span class="label bg-light">{{ trans('messages.delivery_at') }} <span class="material-symbols-rounded">
                    event
                    </span></span></span>
            {{ isset($campaign->delivery_at) ? Auth::user()->customer->formatDateTime($campaign->delivery_at, 'date_full') : "" }}
        </div>
    </div>
</div>
