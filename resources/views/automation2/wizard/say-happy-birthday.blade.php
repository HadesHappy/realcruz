<div class="row">
    <div class="col-md-6">
        <input type="hidden" name="options[type]" value="event" />
        <input type="hidden" name="options[field]" value="date_of_birth" />
        
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => trans('messages.automation.before'),
            'name' => 'options[before]',
            'value' => '',
            'help_class' => 'trigger',
            'options' => Acelle\Model\Automation2::getDelayBeforeOptions(),
        ])

        @include('helpers.form_control', [
            'type' => 'time2',
            'name' => 'options[at]',
            'label' => trans('messages.automation.at'),
            'value' => '10:00 AM',
            'help_class' => 'trigger'
        ])
    </div>
</div>
<div class="row">
    <div class="col-md-6 mt-2">
        @include('helpers.form_control', [
            'name' => 'mail_list_uid',
            'include_blank' => trans('messages.automation.choose_list'),
            'type' => 'select',
            'label' => trans('messages.list'),
            'value' => '',
            'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
        ])
    </div>
</div>
<div class="birthday_field">

</div>

<script>
    var fieldSelect = {
        url: '{{ action('Automation2Controller@wizardListFieldSelect') }}',
        getContainer: function() {
            return $('.birthday_field');
        },
        load: function(list_uid) {
            $.ajax({
                url: this.url,
                method: 'GET',
                data: {
                    list_uid: list_uid
                }
            }).done(function(response) {
                fieldSelect.getContainer().html(response);

                initJs(fieldSelect.getContainer());
            });
        }
    }

    $(function() {
        $('#trigger-select [name=mail_list_uid]').on('change', function() {
            var list_uid = $(this).val();

            fieldSelect.load(list_uid);
        });
    });
</script>