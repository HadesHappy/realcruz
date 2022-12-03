<textarea 
    {{ isset($readonly) ? "readonly='readonly'" : "" }}
    type="text"
    name="{{ $name }}"
    class="form-control{{ $classes }} {{ isset($class) ? $class : "" }}"
    {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }}
>{{ isset($value) ? $value : "" }}</textarea>
