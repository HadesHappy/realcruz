<input
    {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }}
    placeholder="{{ isset($placeholder) ? $placeholder : "" }}"
    {{ isset($required) ? "required" : "" }}
    value="{{ isset($value) ? $value : "" }}"
    type="number"
    name="{{ $name }}"
    class="form-control{{ $classes }} number numeric {{ isset($class) ? $class : "" }}" />
