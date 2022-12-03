<ul class="key-value-list mt-2">
    {{-- <li class="d-flex align-items-center">
        <div class="list-media mr-4">
            <i class="material-symbols-rounded text-success">check</i>
        </div>
        <div class="values mr-auto">
            <label>
                {{ trans('messages.automation.email.recipients_count', ['count' => $automation->mailList->subscribersCount()]) }}
            </label>
            <div class="value">
                {{ $automation->mailList->name }}
            </div>
        </div>
        <div class="list-action">
            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}')" class="btn btn-secondary btn-sm">
                {{ trans('messages.automation.email.setup') }}
            </a>
        </div>
    </li> --}}
    <li class="d-flex align-items-center">
        <div class="list-media mr-4">
            <i class="material-symbols-rounded text-muted">textsms</i>
        </div>
        <div class="values mr-auto">
            <label>
                {{ trans('messages.automation.email.subject') }}
            </label>
            <div class="value">
                {{ $email->subject }}
            </div>
        </div>
        <div class="list-action">
            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}')" class="btn btn-secondary btn-sm">
                {{ trans('messages.automation.email.setup') }}
            </a>
        </div>
    </li>
    <li class="d-flex align-items-center">
        <div class="list-media mr-4">
            <i class="material-symbols-rounded text-muted">my_location</i>
        </div>
        <div class="values mr-auto">
            <label>
                {{ trans('messages.automation.email.from') }}
            </label>
            <div class="value">
                {{ $email->from_email }}
            </div>
        </div>
        <div class="list-action">
            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}')" class="btn btn-secondary btn-sm">
                {{ trans('messages.automation.email.setup') }}
            </a>
        </div>
    </li>
    <li class="d-flex align-items-center">
        <div class="list-media mr-4">
            <i class="material-symbols-rounded text-muted">reply</i>
        </div>
        <div class="values mr-auto">
            <label>
                {{ trans('messages.reply_to') }}
            </label>
            <div class="value">
                @if($email->reply_to)
                    {{ $email->reply_to }}
                @else
                    <span class="text-warning small">
                        <i class="material-symbols-rounded">warning</i>
                        {{ trans('messages.email.no_reply_to') }}
                    </span>
                @endif
            </div>
        </div>
        <div class="list-action">
            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}')" class="btn btn-secondary btn-sm">
                {{ trans('messages.automation.email.setup') }}
            </a>
        </div>
    </li>
    <li class="d-flex align-items-center">
        <div class="list-media mr-4">
            @if($email->content)
                <i class="material-symbols-rounded text-muted">vertical_split</i>
            @else
                <i class="material-symbols-rounded text-muted">vertical_split</i>
            @endif
        </div>
        <div class="values mr-auto">
            <label>
                {{ trans('messages.automation.email.summary.content') }}
            </label>
            <div class="value">
                @if($email->template)
                    {{ trans('messages.automation.email.content.last_edit', [
                        'time' => $email->updated_at->diffForHumans(),
                    ]) }}
                @else
                    <span class="text-danger small">
                        <i class="material-symbols-rounded">error_outline</i>
                        {{ trans('messages.automation.email.no_content') }}
                    </span>
                @endif
            </div>
        </div>
        <div class="list-action">
            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailTemplate', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}')" class="btn btn-secondary btn-sm">
                {{ trans('messages.automation.email.summary.content.update') }}
            </a>
        </div>
    </li>
    <li class="d-flex align-items-center confirm-webhooks-summary">
        <div class="list-media mr-4">
            <i class="material-symbols-rounded text-muted">cable</i>
        </div>
        <div class="values mr-auto">
            <label>
                {{ number_with_delimiter($email->emailWebhooks()->count()) }} {{ trans('messages.webhooks') }}
            </label>
            <div class="value">
                @if($email->emailWebhooks()->count())
                    {{ trans('messages.automation.email.content.last_edit', [
                        'time' => $email->emailWebhooks()->orderBy('updated_at', 'desc')->first()->updated_at->diffForHumans(),
                    ]) }}
                @else
                    <span class="small">
                        {{-- <i class="material-symbols-rounded">error_outline</i> --}}
                        {{ trans('messages.webhooks.empty') }}
                    </span>
                @endif
            </div>
        </div>
        <div class="list-action">
            <a href="javascript:;" class="btn btn-secondary btn-sm manage_webhooks_but">
                {{ trans('messages.webhooks.manage') }}
            </a>
        </div>

        <script>
            $(function() {
                // manage webhooks button click
                $('.manage_webhooks_but').on('click', function(e) {
                    e.preventDefault();

                    EmailConfirm.getWebhooksPopup().load();
                });
            });
        </script>
    </li>
    <li class="d-flex align-items-center">
        <div class="list-media mr-4">
            <i class="material-symbols-rounded text-muted">track_changes</i>
        </div>
        <div class="values mr-auto">
            <label>
                {{ trans('messages.automation.email.tracking') }}
            </label>
            <div class="value">
                @if ($email->track_open)
                    {{ trans('messages.automation.email.opens') }}@if ($email->track_click),@endif
                @endif
                @if ($email->track_click)
                    {{ trans('messages.automation.email.clicks') }}
                @endif
            </div>
        </div>
        <div class="list-action">
            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}')" class="btn btn-secondary btn-sm">
                {{ trans('messages.automation.email.setup') }}
            </a>
        </div>
    </li>
</ul> 

<script>
    var EmailConfirm = {
        webhooksPopup: null,
        getWebhooksPopup: function() {
            if (this.webhooksPopup == null) {
                this.webhooksPopup = new Popup({
                    url: '{{ action('Automation2Controller@webhooks', [
                        'email_uid' => $email->uid,
                    ]) }}',
                    onclose: function() {
                        EmailConfirm.refresh();
                    }
                });
            }

            return this.webhooksPopup;
        },

        refresh: function() {
            // var box = confirm-webhooks-summary
            $.ajax({
                url: "{{ action('Automation2Controller@emailConfirm', [
                    'uid' => $automation->uid,
                    'email_uid' => $email->uid,
                ]) }}",
                method: 'GET',
                data: {
                    _token: CSRF_TOKEN
                },
                success: function (response) {
                    var html = $('<div>').html(response).find('.confirm-webhooks-summary').html();

                    $('.confirm-webhooks-summary').html(html);
                }
            });
        }
    }
</script>
