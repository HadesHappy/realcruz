<a class="mb-4 d-flex align-items-center back-to-automation" href="javascript:;"
    onclick="sidebar.load('{{ action('Automation2Controller@settings', $automation->uid) }}')"
>
    <span class="material-symbols-rounded me-2">
        arrow_back
        </span>
    <span>{{ trans('messages.automation.back_to_automation') }}</span>
</a>