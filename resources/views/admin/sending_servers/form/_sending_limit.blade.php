@include('helpers.form_control', [
    'type' => 'select',
    'class' => '',
    'name' => 'options[sending_limit]',
    'label' => trans('messages.sending_servers.speed_limit'),
    'value' => $server->getOption('sending_limit'),
    'help_class' => 'sending_server',
    'rules' => [],
    'options' => $server->getSendingLimitSelectOptions(),
])

<input type="hidden" name="quota_value" value="{{ $quotaValue }}" />
<input type="hidden" name="quota_base" value="{{ $quotaBase }}" />
<input type="hidden" name="quota_unit" value="{{ $quotaUnit }}" />

<script>
    var SendingLimit = {
        sendingLimitPopup: null,

        getBox: function() {
            return $('.sending-limit-box');
        },

        getSendingLimitPopup: function() {
            if (this.sendingLimitPopup == null) {
                this.sendingLimitPopup = new Popup({
                    url: '{{ action('Admin\SendingServerController@sendingLimit', ['uid' => ($server->uid ? $server->uid : 0)]) }}'
                });
            }

            return this.sendingLimitPopup;
        }
    }

    $(function() {
    });

    $(function() {
        $('[name="options[sending_limit]"]').on('change', function() {
            var val = $(this).val();

            if (val == 'custom') {
                SendingLimit.getSendingLimitPopup().load();
            }
        });
    });
</script>