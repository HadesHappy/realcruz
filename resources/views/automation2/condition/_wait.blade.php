@php
    $id = uniqid();
@endphp
<div id="condition_wait_container_{{ $id }}">
    <div class="mt-4 mb-4">
        <div class="">
            {!! trans('messages.condition.wait.title') !!}
        </div>
        <div class="mb-2 wait-select-container" style="width:100%">
            @include('helpers.form_control', [
                'type' => 'select',
                'class' => 'required',
                'label' => '',
                'name' => 'wait',
                'value' => $element->getOption('wait') ? $element->getOption('wait') : '1 day',
                'required' => true,
                'options' => \Acelle\Model\Automation2::getConditionWaitOptions($element->getOption('wait')),
            ])
        </div>
        <div class="text-muted fst-italic">
            @php
                $vals = explode(' ', ($element->getOption('wait') ? $element->getOption('wait') : '1 day'));
            @endphp
            {!! trans('messages.condition.wait.intro', [
                'days' => trans_choice('messages.count_' . $vals[1], $vals[0]),
            ]) !!}
        </div>
    </div>
</div>

<script>
    var Automation2ConditionWait = {
        labels: $('#condition_wait_container_{{ $id }} .wait_day'),
        selectBox: function() {
            return $('#condition_wait_container_{{ $id }} [name=wait]');
        },
        selectContainer: $('#condition_wait_container_{{ $id }} .wait-select-container'),

        customUrl: '{{ action('Automation2Controller@conditionWaitCustom') }}',

        select: function() {
            var _this = this;

            var val = _this.selectBox().val();            

            if (val == 'custom') {
                _this.showCustomPopup();
                return;
            }
            
            Automation2ConditionWait.updateCurrentVal();
        },

        updateCurrentVal: function() {
            var text = this.selectBox().find('option:selected').html();
            this.labels.html(text);
            this.currentVal = this.selectBox().val();
        },

        init: function() {
            var _this = this;

            _this.selectBox().on('change', function() {
                _this.select();
            });

            Automation2ConditionWait.updateCurrentVal();
        },

        showCustomPopup: function() {
            var _this = this;
            
            _this.customPopup = new Popup({
                url: _this.customUrl,
                onclose: function() {
                    if(_this.selectBox().val() == 'custom') {
                        _this.selectBox().val(_this.currentVal).change();
                    }
                }
            });

            _this.customPopup.load();
        }
    }

    $(function() {
        Automation2ConditionWait.init();
    });
</script>