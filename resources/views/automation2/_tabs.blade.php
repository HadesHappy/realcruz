<ul class="nav nav-tabs mt-3 mb-4 nav-underline">
    <li class="nav-item">
        <a class="nav-link settings" href="javascript:;" onclick="sidebar.load('{{ action('Automation2Controller@settings', $automation->uid) }}')">
            <i class="material-symbols-rounded me-2">menu</i>
            {{ trans('messages.automation.settings') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link insight" href="javascript:;" onclick="sidebar.load('{{ action('Automation2Controller@insight', $automation->uid) }}')">
            <i class="material-symbols-rounded me-2">bubble_chart</i>
            {{ trans('messages.automation.insight') }}
        </a>
    </li>
    <li class="nav-item">
        @if ($automation->getTrigger()->getOption('type') == 'woo-abandoned-cart')
            <a href="javascript:;"
                onclick="timelinePopup.load('{{ action('Automation2Controller@cartStats', $automation->uid) }}')"
                class="nav-link statistics">
                <i class="material-symbols-rounded me-2">multiline_chart</i>
                {{ trans('messages.automation.statistics') }}
            </a>
        @else
            <a href="javascript:;"
                onclick="timelinePopup.load('{{ action('Automation2Controller@contacts', $automation->uid) }}')"
                class="nav-link statistics">
                <i class="material-symbols-rounded me-2">multiline_chart</i>
                {{ trans('messages.automation.statistics') }}
            </a>
        @endif
        <script>
            $('.nav-link.statistics').on('click', function(e) {
                e.preventDefault();

                var link = $(this);

                setTimeout(function() {
                    link.removeClass('active');
                }, 100);
            });
        </script>
            
    </li>
</ul>
    
<script>
    @if (isset($tab))
        $('.nav-link.{{ $tab }}').addClass('active');
    @endif
    @if (isset(request()->type))
        $('.dropdown-item.contacts_{{ request()->type }}').addClass('active');
    @endif
    @if (isset($sub))
        $('.dropdown-item.{{ $sub }}').addClass('active');
    @endif
</script>