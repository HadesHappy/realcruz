@php
    $uid = uniqid();
@endphp
<div class="mb-20">
    <input type="hidden" name="options[type]" value="api" />
    <input type="hidden" name="uid" value="{{ $uid }}" />
    
    @include('helpers.form_control', [
        'type' => 'text',
        'class' => '',
        'readonly' => true,
        'label' => '',
        'name' => 'options[endpoint]',
        'value' => 'POST ' . route('automation_execute', [
            'uid' => $uid,
        ]),
        'help_class' => 'trigger',
        'rules' => [],
    ])

    <h6 class="mt-3">{{ trans('messages.curl_example') }}:</h6>
    <div class="small mb-3 alert bg-light py-1 px-2">
        <code class="text-info" style="word-wrap:normal;">
<pre style="font-size:12px">curl -X POST -H "accept:application/json" -G \
{{ route('automation_execute', [
                'uid' => $uid,
]) }} \
-d api_token={{ \Auth::user()->api_token }}</pre></code>
    </div>

    @include('helpers.form_control', [
        'name' => 'mail_list_uid',
        'include_blank' => trans('messages.automation.choose_list'),
        'type' => 'select',
        'label' => trans('messages.list'),
        'value' => '',
        'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
    ])
</div>