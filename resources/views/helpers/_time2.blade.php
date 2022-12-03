<div>
    <div class="input-icon-right">											
        <input {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }} id="{{ $name }}" placeholder="{{ isset($placeholder) ? $placeholder : "" }}" value="{{ isset($value) ? $value : "" }}" type="text" name="{{ $name }}" class="form-control{{ $classes }} time-selector {{ isset($class) ? $class : "" }}">
        <span class="ico-right"><span class="material-symbols-rounded">
schedule
</span></span>
    </div>
</div>
