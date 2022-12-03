@if ($email->emailLinks()->count())
    @include('helpers.form_control', [
        'type' => 'select',
        'name' => 'email_link_id',
        'value' => request()->email_link_id,
        'label' => trans('messages.webhook.select_email_link'),
        'options' => $email->emailLinks->map(function($link) {
            return ['text' => $link->link, 'value' => $link->id];
        })->toArray(),
        'rules' => ['type' => 'required'],
    ])
@else
    <div class="alert alert-danger">
        {{ trans('messages.emails.link_empty') }}
    </div>
@endif

