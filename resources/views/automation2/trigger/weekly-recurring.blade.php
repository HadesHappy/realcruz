<div class="mb-4">
    <input type="hidden" name="options[type]" value="datetime" />

    <div class="form-group">
        <label>{{ trans('messages.days_of_week') }}<span class="text-danger">*</span></label>
        <div>
            <div class="btn-group day-week-select" role="group" aria-label="Basic example">
                @php
                    $days = $trigger->getOption('days_of_week') ? $trigger->getOption('days_of_week') : []
                @endphp
                @for($i = 0; $i < 7; $i++)
                    <button role="button" class="btn btn-{{ in_array($i, $days) ? 'primary' : 'light' }}">
                        {{ trans('messages.day_of_week.' . $i) }}
                        <input
                            {{ in_array($i, $days) ? 'checked' : '' }}
                            class="day-week-checkbox hide"
                            type="checkbox" name="options[days_of_week][]" value="{{ $i }}" />
                    </button>
                @endfor
            </div>
        </div>
    </div>

    <script>
        $('.day-week-select button').click(function(e) {
            e.preventDefault();

            if ($(this).find('.day-week-checkbox').is(':checked')) {
                $(this).find('.day-week-checkbox').prop('checked', false);
                $(this).removeClass('btn-primary');
                $(this).addClass('btn-light');
            } else {
                $(this).find('.day-week-checkbox').prop('checked', true);
                $(this).addClass('btn-primary');
                $(this).removeClass('btn-light');
            }
        });
    </script>

    @php
        $customer = Auth::user()->customer;

        $time = $trigger->getOption('at');
        if (!$time) {
            $time = $customer->getCurrentTime()->format('h:i A');
        }
    @endphp
    
    @include('helpers.form_control', [
        'type' => 'time2',
        'name' => 'options[at]',
        'label' => trans('messages.automation.at'),
        'value' => $time,
        'rules' => $rules,
        'help_class' => 'trigger'
    ])

    @include('helpers.form_control', [
        'type' => 'select',
        'name' => 'timezone',
        'value' => Auth::user()->customer->timezone,
        'options' => Tool::getTimezoneSelectOptions(),
        'include_blank' => trans('messages.choose'),
        'disabled' => true
    ])
</div>