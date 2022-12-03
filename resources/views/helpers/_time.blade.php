<div class="input-icon-right position-relative">											
    <input {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }} id="{{ $name }}" placeholder="{{ isset($placeholder) ? $placeholder : "" }}" value="{{ isset($value) ? $value : "" }}" type="text" name="{{ $name }}" class="form-control{{ $classes }} pickatime{{ isset($class) ? $class : "" }}">
    <span class="time-input-icon"><span class="material-symbols-rounded">
schedule
</span></span>
</div>
