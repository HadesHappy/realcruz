@if ($server->type)
    <div class="row">
        @foreach ($server->getConfig()["fields"] as $field)
            <div class="col-md-4">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => '',
                    'name' => 'options[' . $field . ']',
                    'value' => isset($options[$field]) ? $options[$field] : '',
                    'label' => trans('messages.verification_' . $field ),
                    'help_class' => 'email_verification_server',
                    'rules' => $server->rules()
                ])
            </div>
        @endforeach
    </div>
@endif
