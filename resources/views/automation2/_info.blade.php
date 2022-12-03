@if (!is_null($automation->last_error))
    <div class="mb-4">
        @include('elements._notification', [
            'level' => 'warning',
            'message' => '<h4>Execution Error</h4>
                <p>' . $automation->last_error . '</p>'
        ])
    </div>
@endif

<div class="sidebar-header d-flex flex-center mb-2">
    <h5 class="m-0 mr-auto">{{ $automation->name }}</h5>
    <div class="d-flex align-items-center campaign-status-section">
        <span class="mr-auto small pe-2">{{ trans('messages.automation.status.' . $automation->status) }}</span>
        <label>
            @include('helpers.form_control', [
                'type' => 'checkbox',
                'name' => 'automation_status',
                'label' => '',
                'class' => 'automation_status styled3',
                'value' => ($automation->status == \Acelle\Model\Automation2::STATUS_ACTIVE ? true : false),
                'options' => [false,true],
                'help_class' => '',
                'rules' => []
            ])
        </label>
    </div>
</div>
<div class="d-flex align-items-center mb-4">
    <p class="pr-4 mb-0">
        {!! $automation->getIntro() !!}
    </p>
    <div>
        <a href="javascript:;" class="btn btn-info text-nowrap d-flex align-items-center" onclick="sidebar.load('{{ action('Automation2Controller@settings', $automation->uid) }}')">
            <i class="material-symbols-rounded me-2">auto_graph</i> {{ trans('messages.automation.settings') }}
        </a>
    </div>         
</div>
    
<script>
    // change automation status
    $('[name="automation_status"]').change(function() {
        var value = $(this).is(":checked");
        var url, confirm;
        
        if (value) {
            url = '{{ action('Automation2Controller@enable', ["uids" => $automation->uid]) }}';
            confirm = '{!! trans('messages.automation.enable.confirm', ['name' => $automation->name]) !!}';
        } else {
            url = '{{ action('Automation2Controller@disable', ["uids" => $automation->uid]) }}';
            confirm = '{!! trans('messages.automation.disable.confirm', ['name' => $automation->name]) !!}';
        }

        var dialog = new Dialog('confirm', {
            message: confirm,
            ok: function(dialog) {
                $.ajax({
                    url: url,
                    type: 'PATCH',
                    globalError: false,
                    data: {
                        _token: CSRF_TOKEN
                    }
                }).done(function(response) {
                    if (!value) {
                        notify(response.status, '{{ trans('messages.notify.success') }}', response.message);
                    } else {
                        var dialog = new Dialog('alert', {
                            title: '{{ trans('messages.automation.started.title') }}  ',
                            message: `{!! trans('messages.automation.started.desc') !!}`,
                            ok: function(dialog) {        
                                sidebar.load();                    
                            },
                            cancel: function(dialog) {
                                sidebar.load();
                            },
                            close: function(dialog) {
                                sidebar.load();
                            },
                        });
                    }
                
                    sidebar.load();
                }).fail(function(e) {
                    var error = JSON.parse(e.responseText);
                    notify({
                        type: 'danger',
                        title: '{{ trans('messages.notify.error') }}',
                        message: error.message
                    });
                    sidebar.load();
                });      
            },
            cancel: function(dialog) {
                sidebar.load();
            },
            close: function(dialog) {
                sidebar.load();
            },
        });
          
    });
</script>