<div class="input-icon-right">											
    <input {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }} id="{{ $name }}" placeholder="{{ isset($placeholder) ? $placeholder : "" }}" value="{{ isset($value) ? $value : "" }}" type="text" name="{{ $name }}" class="form-control{{ $classes }} pickadatetime{{ isset($class) ? $class : "" }}">
    <span class="date-input-icon"><span class="material-symbols-rounded">
schedule
</span></span>
</div>
