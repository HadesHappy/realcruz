<div class="flex space-between">
    <label class="mr-30">
        @if (isset($help_class) && Lang::has('messages.' . $help_class . '.' . $name . '.help'))
            <span class="checkbox-description">
                {!! trans('messages.' . $help_class . '.' . $name . '.help') !!}
            </span>
        @endif
    </label>
    <div>
        <input type="hidden" name="{{ $name }}" value="{{ ($options[0] == false) ? 0 : $options[0] }}" />
        <input{{ $value == $options[1] ? " checked" : "" }} {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }} type="checkbox" id="{{ $name }}" name="{{ $name }}" value="{{ $options[1] }}" class="switchery {{ isset($class) ? $class : "" }}" data-on-text="On" data-off-text="Off" data-on-color="success" data-off-color="default">
    </div>
</div>


