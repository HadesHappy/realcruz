@if (!isset($disabled) || $disabled === false)
    <input type="hidden" name="{{ $name }}" value="{{ $options[0] }}" />
@endif

<div class="checkbox inline text-semibold {{ isset($class) ? $class : "" }} {{ isset($disabled) && $disabled == true ? ' disabled' : "" }}">
    <label>
        <span class="d-flex align-items-center">
            <input {{ $value == $options[1] ? " checked" : "" }}
                {{ !isset($value) && isset($default_value) && $default_value == $options[1] ? " checked" : "" }}
                {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }}
                name="{{ $name }}" value="{{ $options[1] }}"
                class="styled me-2 {{ $classes }}  {{ isset($class) ? $class : "" }}"
                type="checkbox" class="styled">
            <span style="padding-top: 3px" class="ms-2">{!! $label !!}</span>
        </span>
    </label>
</div>
