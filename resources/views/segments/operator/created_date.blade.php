<div class="row">
    <div class="col-md-6 operator-col">
        @include('helpers.form_control', [
            'type' => 'select',
            'name' => 'conditions['.$index.'][operator]',
            'label' => '',
            'value' => (isset($operator) ? $operator : ''),
            'options' => Acelle\Model\Segment::createdDateOperators()
        ])
    </div>
    <div class="col-md-6 value-col created_date_value">

    </div>
</div>

<script>
    var SegmentsOperatorCreatedDate = {
        data: {
            text: `
                @include('helpers.form_control', [
                    'type' => 'number',
                    'name' => 'conditions['.$index.'][value]',
                    'label' => '',
                    'value' => (isset($value) ? $value : '')
                ])
            `,
            date: `
                @php
                    if (isset($value) && $condition->operator !== 'created_date_last_x_days') {
                        $zone = Auth::user()->customer->getTimezone();
                        $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC');
                        $date->setTimezone($zone);
                        $value = $date->format('Y-m-d, H:i');
                    }
                @endphp
                @include('helpers.form_control', [
                    'type' => 'datetime',
                    'name' => 'conditions['.$index.'][value]',
                    'label' => '',
                    'value' => (isset($value) ? $value : ''),
                ])
            `
        },
        loadValue: function(operator) {
            if (operator == 'created_date_last_x_days') {
                $('.created_date_value').html(this.data.text);
            } else {
                $('.created_date_value').html(this.data.date);
            }

            initJs($('.created_date_value'));
        }
    }

    $(function() {
        // current value
        SegmentsOperatorCreatedDate.loadValue($('[name="conditions[{{ $index }}][operator]"]').val());

        // value changing 
        $('[name="conditions[{{ $index }}][operator]"]').on('change', function() {
            var operator = $(this).val();
            SegmentsOperatorCreatedDate.loadValue(operator);
            $('[name="conditions[{{ $index }}][value]"]').val('');
        });
    });
</script>