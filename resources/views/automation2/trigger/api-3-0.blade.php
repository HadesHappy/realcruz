<div class="mb-20">
    <input type="hidden" name="options[type]" value="api" />
    
    @include('helpers.form_control', [
        'type' => 'text',
        'class' => '',
        'readonly' => true,
        'label' => '',
        'name' => 'options[endpoint]',
        'value' => 'POST ' . route('automation_execute', [
            'uid' => $automation->uid,
        ]),
        'help_class' => 'trigger',
        'rules' => [],
    ])

    <h6 class="mt-3">{{ trans('messages.curl_example') }}:</h6>
    <div class="small mb-3 alert bg-light py-1 px-2">
        <code class="text-info" style="word-wrap:normal;">
<pre style="font-size:12px">curl -X POST -H "accept:application/json" -G \
{{ route('automation_execute', [
                'uid' => $automation->uid,
]) }} \
-d api_token={{ \Auth::user()->api_token }}</pre></code>
    </div>
</div>