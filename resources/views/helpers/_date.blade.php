<div class="input-icon-right position-relative">											
    <input {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }} id="{{ $name }}" placeholder="{{ isset($placeholder) ? $placeholder : "" }}" value="{{ isset($value) ? $value : "" }}" type="text" name="{{ $name }}" class="control-with-mask pickadate-control form-control{{ $classes }} pickadate{{ isset($class) ? $class : "" }}">
    <span class="mask-control date-mask-control"></span>
    <span class="date-input-icon"><span class="material-symbols-rounded">
event
</span></span>
</div>
