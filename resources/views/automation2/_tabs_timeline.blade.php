<ul class="nav nav-tabs nav-underline mb-4 timeline-tab">
    <li class="nav-item dropdown">
        <a href="javascript:;" class="nav-link {{ controllerAction() == 'Automation2Controller@contacts' ? 'active' : '' }}" onclick="timelinePopup.load('{{ action('Automation2Controller@contacts', [
            'uid' => $automation->uid,
        ]) }}')">
            {{ trans('messages.automation.audience') }}
        </a>
    </li>
    <li class="nav-item dropdown">
        <a href="javascript:;" class="nav-link {{ controllerAction() == 'Automation2Controller@timeline' ? 'active' : '' }}" onclick="timelinePopup.load('{{ action('Automation2Controller@timeline', $automation->uid) }}')">
            {{ trans('messages.automation.timeline') }}
        </a>
    </li>
</ul>
    
<script>
    @if (isset($tab))
        $('.timeline-tab .nav-link.{{ $tab }}').addClass('active');
    @endif
</script>