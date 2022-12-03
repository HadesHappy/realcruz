<div class="row">
    <div class="col-sm-6 col-md-4">
        @include('helpers.form_control', [
            'type' => 'text',
            'class' => '',
            'name' => 'name',
            'value' => $server->name,
            'help_class' => 'feedback_loop_handler',
            'rules' => Acelle\Model\FeedbackLoopHandler::rules()
        ])
    </div>
    <div class="col-sm-6 col-md-4">
        @include('helpers.form_control', [
            'type' => 'text',
            'class' => '',
            'name' => 'host',
            'value' => $server->host,
            'help_class' => 'feedback_loop_handler',
            'rules' => Acelle\Model\FeedbackLoopHandler::rules()
        ])
    </div>
    <div class="col-sm-6 col-md-4">
        @include('helpers.form_control', [
            'type' => 'text',
            'class' => '',
            'name' => 'port',
            'value' => $server->port,
            'help_class' => 'feedback_loop_handler',
            'rules' => Acelle\Model\FeedbackLoopHandler::rules()
        ])
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-4">
        @include('helpers.form_control', [
            'type' => 'text',
            'class' => '',
            'name' => 'username',
            'value' => $server->username,
            'help_class' => 'feedback_loop_handler',
            'rules' => Acelle\Model\FeedbackLoopHandler::rules()
        ])
    </div>
    <div class="col-sm-6 col-md-4">
        @include('helpers.form_control', [
            'type' => 'text',
            'class' => '',
            'name' => 'password',
            'value' => $server->password,
            'help_class' => 'feedback_loop_handler',
            'rules' => Acelle\Model\FeedbackLoopHandler::rules()
        ])
    </div>
    <div class="col-sm-6 col-md-4">
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'name' => 'protocol',
            'value' => $server->protocol,
            'options' => Acelle\Model\FeedbackLoopHandler::protocolSelectOptions(),
            'help_class' => 'feedback_loop_handler',
            'rules' => Acelle\Model\FeedbackLoopHandler::rules()
        ])
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-4">
        @include('helpers.form_control', [
            'type' => 'text',
            'class' => 'email',
            'name' => 'email',
            'value' => $server->email,
            'help_class' => 'feedback_loop_handler',
            'rules' => Acelle\Model\FeedbackLoopHandler::rules()
        ])
    </div>
    <div class="col-sm-6 col-md-4">
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'name' => 'encryption',
            'value' => $server->encryption,
            'options' => Acelle\Model\FeedbackLoopHandler::encryptionSelectOptions(),
            'help_class' => 'feedback_loop_handler',
            'rules' => Acelle\Model\FeedbackLoopHandler::rules()
        ])
    </div>
</div>
<hr>
<div class="text-left">
    @can('test', $server)
        <a
            href="{{ action('Admin\FeedbackLoopHandlerController@test', $server->uid) }}"
            role="button"
            class="btn btn-primary me-1 test-button"
            data-in-form="true"
            mask-title="{{ trans('messages.feedback_loop_handler.testing_connection') }}"
        >
            <span class="material-symbols-rounded">
quiz
</span> {{ trans('messages.feedback_loop_handler.test') }}
        </a>
    @endcan
    <button class="btn btn-secondary"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
</div>

<script>
    $(function() {
        $('.test-button').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            addMaskLoading();

            new Link({
                type: 'ajax',
                url: url,
                method: 'POST',
                done: function(response) {
                    new Dialog('alert', {
                        message: response.message,
                    });

                    removeMaskLoading();
                },
                data: {
                    _token: CSRF_TOKEN
                }
            });
        });
            
    });
</script>