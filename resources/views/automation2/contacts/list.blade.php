@if ($contacts->count() > 0)
    <p class="insight-intro mb-2 small">
        {{ trans('messages.automation.contact.all_count', ['count' => number_with_delimiter($contacts->total(), $precision = 0)]) }}
    </p>
        
    <div class="mc-table small border-top">
        @foreach ($contacts as $key => $contact)
            @php
                $trigger = $automation->getAutoTriggerFor($contact);
            @endphp
            <div class="mc-row d-flex align-items-center">
                <div class="media trigger">
                    <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', [
                        'uid' => $automation->uid,
                        'contact_uid' => $contact->uid,
                    ]) }}')" class="font-weight-semibold d-block">
                        @if ($contact->avatar)
                            <img src="{{ action('SubscriberController@avatar',  $contact->uid) }}" haha="https://i.pravatar.cc/30{{ $key }}" />
                        @elseif(isSiteDemo())
                            <img src="https://i.pravatar.cc/30{{ $key }}" />
                        @else
                            <i style="opacity: 0.7" class="material-symbols-rounded bg-{{ rand_item(['info', 'success', 'secondary', 'primary', 'danger', 'warning']) }}">person_outline</i>
                        @endif
                    </a>
                </div>
                <div class="flex-fill" style="width: 20%">
                    <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', [
                        'uid' => $automation->uid,
                        'contact_uid' => $contact->uid,
                    ]) }}')" class="font-weight-semibold d-block">
                        {{ $contact->getFullName() }}
                    </a>
                    <desc>{{ $contact->email }}</desc>
                </div>
                
                <div class="actions-points">
                    @if (!is_null($trigger))
                        @php
                            $points = $trigger->getExecutedActions();
                        @endphp

                        @if (empty($points))
                            <span>{{ trans('messages.automation.status.triggered.desc') }}</span>
                        @endif

                        @foreach ($points as $action)
                            <span class="xtooltip round-action-point action-{{ $action->getType() }}" title="{{ $action->getProgressDescription(). sprintf(' (%s)', $action->getLastExecutedHumanReadable()) }}">
                            </span>
                        @endforeach
                    @endif
                </div>
                <div class="flex-fill text-end">
                    @if (is_null($trigger))
                        <label title="" class="text-end">
                            <span class="">
                                <span class="text-warning">{{ trans('messages.automation.contacts.trigger.waiting') }}</span>
                            </span>
                        </label>
                        <desc>
                            <a target="_blank" style="color:blue;text-decoration: underline;"
                                href="{{ action('Automation2Controller@triggerNow', [ 'automation' => $automation->uid, 'subscriber' => $contact->uid ]) }}"
                                class="timeline-trigger-now">
                                {{ trans('messages.automation.trigger_now') }}
                            </a>
                        </desc>
                    @else
                        <label title="" class="text-end">
                            <span class="text-truncate" style="max-width: 100px;margin-right:0px">
                                @php
                                    $latestAction = $trigger->getLatestAction() ?: $trigger->getTrigger();
                                    $lastUpdate = $latestAction->getLastExecutedHumanReadable();

                                    if (is_null($lastUpdate)) {
                                        $lastUpdate = $trigger->created_at->diffForHumans(); // Triggered but not yet executed
                                    }
                                @endphp

                                {{ $lastUpdate }}
                            </span>
                        </label>
                        <desc>{{ trans('messages.automation.last_activity') }} •
                            <a target="_blank" target="_blank" style="color:blue;text-decoration: underline;"
                                href="{{ action('AutoTrigger@check', [ 'id' => $trigger->id ]) }}"
                                class="trigger-check"
                            >
                                {{ trans('messages.trigger.check') }}
                            </a>
                        </desc>
                    @endif
                    
                </div>
            </div>
        @endforeach
        
    @include('helpers._pagination', ['paginator' => $contacts] )
@else
    <div class="empty-list">
        <i class="lnr lnr-users"></i>
        <span class="line-1">
            {{ trans('messages.automation.empty_contacts') }}
        </span>
    </div>
@endif

<script>
    $('.timeline-trigger-now').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');

        var dia = new Dialog('confirm', {
            message: `{{ trans('messages.automation.trigger_now.confirm') }}`,
            ok: function() {
                $(this).addClass('link-disabled');
                addMaskLoading();

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (response) {
                        // notify
                        // notify(response.status, '{{ trans('messages.notify.success') }}', response.message); 

                        listContact.load();

                        removeMaskLoading();
                    }
                });
            }
        });
    });

    $('.trigger-check').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');

        $(this).addClass('link-disabled');
        addMaskLoading();

        $.ajax({
            url: url,
            method: 'GET',
            success: function (response) {
                // notify
                // notify(response.status, '{{ trans('messages.notify.success') }}', response.message); 

                listContact.load();

                removeMaskLoading();
            }
        }).always(function() {
            listContact.load();
            removeMaskLoading();
        });
    });
</script>