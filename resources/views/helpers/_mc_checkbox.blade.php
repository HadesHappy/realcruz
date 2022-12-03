<input type="hidden" name="{{ $name }}" value="{{ $options[0] }}" />

<div class="checkbox inline text-semibold {{ isset($class) ? $class : "" }}">
    <label class="mc-text-semibold">
        <input {{ $value == $options[1] ? " checked" : "" }}
            {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }}
            id="{{ $name }}" name="{{ $name }}" value="{{ $options[1] }}"
            class="styled {{ $classes }}  {{ isset($class) ? $class : "" }}"
            type="checkbox" class="styled">
        {{ $label }}
    </label>
</div>
