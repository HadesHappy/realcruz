@include('helpers.form_control', [
    'type' => 'text',
    'class' => '',
    'name' => 'host',
    'value' => $server->host,
    'help_class' => 'sending_server',
    'rules' => ['host' => 'required'],
    'readonly' => true,
])