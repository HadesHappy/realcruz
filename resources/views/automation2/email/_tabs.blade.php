<div class="d-flex align-items-center mb-4">
    <div style="width: 70%" class="mr-auto">
        <h2 class="mb-2 email_title">{{ trans('messages.automation.automation_email') }}</h2>
        <p>{{ trans('messages.automation.automation_email.intro') }}</p>
    </div>    
    <div class="header-action">
        <button class="btn btn-secondary d-flex align-items-center back_to_workflow" onclick="sidebar.load(); popup.hide()">
            <i class="material-symbols-rounded me-2">
                multiline_chart
            </i>
            {{ trans('messages.automation.back_to_workflow') }}
        </button>
    </div>  
</div>

<ul class="nav nav-tabs mb-4 email_tabs nav-underline">
    <li class="nav-item">
        <a class="nav-link setup" href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
            'uid' => $automation->uid,
            'email_uid' => $email->uid,
        ]) }}')">
            <span class="material-symbols-rounded">
settings
</span>
            {{ trans('messages.automation.email.setup') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link template {{ (!isset($email->id)) ? 'disabled' : '' }}" href="javascript:;"
            @if (isset($email->id))                    
                onclick="popup.load('{{ action('Automation2Controller@emailTemplate', [
                    'uid' => $automation->uid,
                    'email_uid' => $email->uid,
                ]) }}')"
            @endif
        >
            <i class="material-symbols-rounded">
mark_email_unread
</i>
            {{ trans('messages.automation.email.content') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link confirm {{ (!isset($email->id)) ? 'disabled' : '' }}" href="javascript:;"
            @if (isset($email->id))                    
                onclick="popup.load('{{ action('Automation2Controller@emailConfirm', [
                    'uid' => $automation->uid,
                    'email_uid' => $email->uid,
                ]) }}')"
            @endif
        >
            <span class="material-symbols-rounded">
                done_all
</span>
            {{ trans('messages.automation.email.confirm') }}
        </a>
    </li>
</ul>
    
<script>
    @if (isset($tab))
        $('.email_tabs .nav-link.{{ $tab }}').addClass('active');
    @endif
    @if (isset($sub))
        $('.email_tabs .dropdown-item.{{ $sub }}').addClass('active');
    @endif
</script>