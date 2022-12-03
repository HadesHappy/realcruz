@include('automation2._info')
				
@include('automation2._tabs', ['tab' => 'settings'])
    
<p class="mt-3">
    {!! trans('messages.automation.settings.intro') !!}
</p>
    
<form id="automationUpdate" action="{{ action("Automation2Controller@update", $automation->uid) }}" method="POST" class="form-validate-jqueryz">
    {{ csrf_field() }}
    
    <div class="row mb-3">
        <div class="col-md-8">
            @include('helpers.form_control', [
                'type' => 'text',
                'class' => '',
                'label' => trans('messages.automation.automation_name'),
                'name' => 'name',
                'value' => $automation->name,
                'help_class' => 'automation',
                'rules' => $automation->rules(),
            ])
            
            @include('helpers.form_control', [
                'name' => 'mail_list_uid',
                'type' => 'select',
                'label' => trans('messages.automation.change_mail_list'),
                'value' => (is_object($automation->mailList) ? $automation->mailList->uid : ''),
                'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
                'rules' => $automation->rules(),
            ])

            <div class="automation-segment">

            </div>
            
            @include('helpers.form_control', [
                'type' => 'select',
                'name' => 'timezone',
                'value' => \Auth::user()->customer->timezone,
                'options' => Tool::getTimezoneSelectOptions(),
                'include_blank' => trans('messages.choose'),
                'rules' => $automation->rules(),
                'disabled' => true,
            ])
        </div>
    </div>
    
    <button class="btn btn-secondary mt-20">{{ trans('messages.automation.settings.save') }}</button>            
</form>

<div class="mt-4 d-flex py-3">
    <div>
        <h4 class="mb-2">
            {{ trans('messages.automation.dangerous_zone') }}
        </h4>
        <p class="">
            {{ trans('messages.automation.delete.wording') }}        
        </p>
        <div class="mt-3">
            <a href="{{ action('Automation2Controller@delete', ['uids' => $automation->uid]) }}"
                data-confirm="{{ trans('messages.automation.delete.confirm') }}"
                class="btn btn-secondary automation-delete"
            >
                <span class="material-symbols-rounded">
delete
</span> {{ trans('messages.automation.delete_automation') }}
            </a>
        </div>
    </div>
</div>
    
<script>
    // automation segment
    var automationSegment = new Box($('.automation-segment'));
    $('[name=mail_list_uid]').change(function(e) {
        var url = '{{ action('Automation2Controller@segmentSelect') }}?uid={{ $automation->uid }}&list_uid=' + $(this).val();

        automationSegment.load(url);
    });
    $('[name=mail_list_uid]').change();


    // set automation name
    setAutomationName('{{ $automation->name }}');

    $('#automationUpdate').submit(function(e) {
        e.preventDefault();
        
        var form = $(this);
        var url = form.attr('action');
        
        // loading effect
        sidebar.loading();
        
        $.ajax({
            url: url,
            method: 'POST',
            data: form.serialize(),
            statusCode: {
                // validate error
                400: function (res) {
                   sidebar.loadHtml(res.responseText);
                }
             },
             success: function (response) {
                sidebar.load();
                
                notify(response.status, '{{ trans('messages.notify.success') }}', response.message);

                // need to reload to update tree data
                location.reload();
             }
        });
    });

    var $sel = $('[name=mail_list_uid]').on('change', function() {
        if ($sel.data('confirm') == 'false') {
            confirm = `{{ trans('messages.automation.change_list.confirm') }}`;

            var dialog = new Dialog('confirm', {
                message: confirm,
                ok: function(dialog) {
                    // store new value        
                    $sel.trigger('update');     
                },
                cancel: function(dialog) {
                    // reset
                    $sel.trigger('restore');
                },
                close: function(dialog) {
                    // reset
                    $sel.trigger('restore');
                },
            });
        }
    }).on('restore', function() {
        $(this).data('confirm', 'true');
        $(this).val($(this).data('currVal')).change();
        $(this).data('confirm', 'false');
    }).on('update', function() {
        $(this).data('currVal', $(this).val());
        $(this).data('confirm', 'false');
    }).trigger('update');

    $('.automation-delete').click(function(e) {
        e.preventDefault();
        
        var confirm = $(this).attr('data-confirm');
        var url = $(this).attr('href');

        var dialog = new Dialog('confirm', {
            message: confirm,
            ok: function(dialog) {
                //
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    data: {
                        _token: CSRF_TOKEN,
                    },
                    statusCode: {
                        // validate error
                        400: function (res) {
                            console.log('Something went wrong!');
                        }
                    },
                    success: function (response) {
                        addMaskLoading(
                            '{{ trans('messages.automation.redirect_to_index') }}',
                            function() {
                                window.location = '{{ action('Automation2Controller@index') }}';
                            },
                            { wait: 2000 }
                        );

                        // notify
                        notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});
                    }
                });
            },
        });
    });
</script>
