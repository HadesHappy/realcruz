<div class="insight-topine d-flex small align-items-center">
    <div class="insight-desc mr-auto pe-3">
        {{ trans('messages.automation.timline.intro') }}
    </div>
    <div class="insight-action">
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group btn-group-sm" role="group">
                <button id="btnGroupDrop1" role="button" class="btn btn-secondary dropdown-toggle timeline-sort-title" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if (request()->sortBy) 
                        {{ trans('messages.timeline.sort.' . request()->sortBy) }}
                    @else
                        {{ trans('messages.timeline.sort') }}
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item timeline-sort" href="#" data-sort="auto_triggers.updated_at">{{ trans('messages.timeline.sort.auto_triggers.updated_at') }}</a>
                    <a class="dropdown-item timeline-sort" href="#" data-sort="auto_triggers.created_at">{{ trans('messages.timeline.sort.auto_triggers.created_at') }}</a>
                    <a class="dropdown-item timeline-sort" href="#" data-sort="subscribers.created_at">{{ trans('messages.timeline.sort.subscribers.created_at') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($timelines->count() > 0)
        <p class="insight-intro mb-2 mt-3 small">
        {{ trans('messages.automation.all_activities') }}
    </p>
        
    <div class="mc-table small border-top">
        @foreach ($timelines as $key => $timeline)
            <div class="mc-row d-flex align-items-center">
                <div class="media trigger">
                    <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', [
                        'uid' => $automation->uid,
                        'contact_uid' => $timeline->subscriber->uid,
                    ]) }}')" class="font-weight-semibold d-block">
                        @if ($timeline->subscriber->avatar)
                            <img src="{{ action('SubscriberController@avatar',  $timeline->subscriber->uid) }}" />
                        @elseif(isSiteDemo())
                            <img src="https://i.pravatar.cc/30{{ $key }}" />
                        @else
                            <i style="opacity: 0.7" class="lnr lnr-user bg-{{ rand_item(['info', 'success', 'secondary', 'primary', 'danger', 'warning']) }}"></i>
                        @endif                        
                    </a>
                </div>
                <div class="flex-fill flex-grow-1" style="width: 50%">
                    <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', [
                        'uid' => $automation->uid,
                        'contact_uid' => $timeline->subscriber->uid,
                    ]) }}')" class="font-weight-semibold d-block">
                        {{ $timeline->subscriber->getFullName() }}
                    </a>
                    <desc>{{ $timeline->activity }}</desc>
                </div>
                <div class="flex-fill text-center">
                    <desc>{{ $timeline->created_at->diffForHumans() }}</desc>
                </div>
            </div>
        @endforeach
    </div>
        
    
@else
    <div class="empty-list">
        <i class="material-symbols-rounded">timeline</i>
        <span class="line-1">
            {{ trans('messages.automation.timeline.no_activities') }}
        </span>
    </div>
@endif

<script>
    function timelineSort(newData) {
        var currentData = listTimeline.data();
        listTimeline.data = function() {
            $.extend( currentData, newData);
            return currentData;
        };

        listTimeline.load();
    }

    $('.timeline-sort-direction').on('click', function(e) {
        e.preventDefault();
        var direction = $(this).attr('data-direction');

        if (direction == 'asc') {
            $(this).attr('data-direction', 'desc');
            timelineSort({sortOrder: 'desc'});
        } else {
            $(this).attr('data-direction', 'asc');
            timelineSort({sortOrder: 'asc'});
        }
    });

    $('.timeline-sort').on('click', function(e) {
        e.preventDefault();
        var sortBy = $(this).attr('data-sort');
        var text = $(this).html();

        timelineSort({sortBy: sortBy, sortOrder: 'desc'});

        $('.timeline-sort-title').html(text);
    });
</script>
    
