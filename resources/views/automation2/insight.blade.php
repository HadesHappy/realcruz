@include('automation2._info')
				
@include('automation2._tabs', ['tab' => 'insight'])
    
<div class="insight-topine flex small">
    <div class="insight-desc mr-auto pe-3">
        {{ trans('messages.automation.your_overview') }}
    </div>
    <div class="insight-time">
        <i class="lnr lnr-clock"></i> {{ trans('messages.automation.started_at', ['time' => $automation->getStartedTime()]) }}
    </div>
</div>
    
<div class="insight-stat-brief d-flex mt-3 mb-4">
    <a title="{{ trans('messages.automation.go_contacts') }}" href="javascript:;"
      onclick="timelinePopup.load('{{ action('Automation2Controller@contacts', [
        'uid' => $automation->uid,
        'type' => 'in_action',
      ]) }}')" class="xtooltip insight-stat-col flex-fill">
        <number>{{ number_with_delimiter($stats['total'], $precision = 0) }}</number>
        <desc class="text-muted text-center">
            <span class="stats-title">{{ trans_choice('messages.automation.contacts', $stats['total']) }}</span>
        </desc>
    </a>
    <a title="{{ trans('messages.automation.go_contacts') }}" href="javascript:;"
      onclick="timelinePopup.load('{{ action('Automation2Controller@contacts', [
        'uid' => $automation->uid,
      ]) }}')" class="xtooltip insight-stat-col flex-fill">
        <number>{{ number_with_delimiter($stats['involed']) }}</number>
        <desc class="text-muted text-center">
            <span class="stats-title">{{ trans_choice('messages.automation.involved', $stats['total']) }}</span>
        </desc>
    </a>
    <a title="{{ trans('messages.automation.go_contacts') }}" href="javascript:;"
      onclick="timelinePopup.load('{{ action('Automation2Controller@contacts', [
        'uid' => $automation->uid,
        'type' => 'in_action',
      ]) }}')" class="xtooltip insight-stat-col flex-fill">
        <number>{{ number_to_percentage($stats['complete']) }}</number>
        <desc class="text-muted text-center">
            <span class="stats-title">{{ trans_choice('messages.automation.complete_percent', $stats['total']) }}</span>
        </desc>
    </a>
</div>
    
<p class="insight-intro">
    {{ trans('messages.automation.insight.intro') }}
</p>
    
<div class="mc-table small mt-3">
    @foreach ($insight as $key => $element)
        @php
            $action = $automation->getElement($key);
        @endphp

        <div class="mc-row d-flex align-items-center">
            <div class="media trigger">
                {!! $action->getIcon() !!}
            </div>
            <div class="flex-fill" style="width: 35%">
                <label title="{{ trans('messages.automation.go_contacts') }}" onclick="timelinePopup.load('{{ action('Automation2Controller@contacts', [
                    'uid' => $automation->uid,
                  ]) }}')" class="cursor-pointer xtooltip font-weight-semibold"
                >
                    {{ $action->getName() }}
                </label>
                <desc title="{{ trans('messages.automation.go_contacts') }}" onclick="timelinePopup.load('{{ action('Automation2Controller@contacts', [
                    'uid' => $automation->uid,
                  ]) }}')" class="cursor-pointer xtooltip">
                    {{ $element['subtitle'] }}
                </desc>
            </div>
            <a 
                title="{{ trans('messages.automation.go_timeline') }}"
                href="javascript:;"
                onclick="timelinePopup.load('{{ action('Automation2Controller@timeline', [
                    'uid' => $automation->uid,
                  ]) }}')"
                class="xtooltip flex-fill"
            >
                <label class="font-weight-semibold">
                    {{ \Carbon\Carbon::parse($element['latest_activity'])->diffForHumans() }}
                </label>
                <desc>{{ trans('messages.automation.action.last_updated') }}</desc>
            </a>
            <div class="flex-fill text-center">
                <h3 title="{{ trans('messages.automation.insight.percent_tip') }}" onclick="timelinePopup.load('{{ action('Automation2Controller@contacts', [
                    'uid' => $automation->uid,
                  ]) }}')" class="cursor-pointer xtooltip font-weight-semibold"
                >
                    {{ number_to_percentage($element['percentage']) }}
                </h3>
            </div>
        </div>
            
    @endforeach
</div>
