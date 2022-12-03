<div class="checkbox3">    
    <div class="checkbox3-desc pr-4" style="width: 100%">
        @if (!empty($label))
            <div class="fw-600">{!! $label !!}</div>
        @endif
    
        @if (isset($help_class) && Lang::has('messages.' . $help_class . '.' . $name . '.help'))
            <span class="checkbox-description">
                {!! trans('messages.' . $help_class . '.' . $name . '.help') !!}
            </span>
        @endif
    </div>
    <div class="switch3" {!! isset($readonly) && $readonly == true ? ' style="pointer-events:none;opacity:0.5;"' : "" !!}>
        <!-- value="{{ false }}" will result in value="", so it is safe to set it to 0 in case of false -->
        <input type="hidden" name="{{ $name }}" value="{{ ($options[0] == false) ? 0 : $options[0] }}" />
        <label><input{{ $value == $options[1] ? " checked" : "" }} {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }} type="checkbox" name="{{ $name }}" value="{{ $options[1] }}" class="switchery {{ $classes }} {{ isset($class) ? $class : "" }}" data-on-text="On" data-off-text="Off" data-on-color="success" data-off-color="default"></label>
    </div>
</div>