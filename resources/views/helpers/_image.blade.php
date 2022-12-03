<div class="row">
    <div class="col-md-9">
        <input {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }} id="{{ $name }}" placeholder="{{ isset($placeholder) ? $placeholder : "" }}" value="{{ isset($value) ? $value : "" }}" type="file" name="{{ $name }}" class="form-control file-styled-primary{{ $classes }} {{ isset($required) ? " required" : "" }}">
    </div>
    <div class="col-md-3">
        @if (isset($value) && !empty($value))
            <img style="background-color: #ccc " width="100%" src="{{ isset($value) ? \URL::asset($value) : "" }}" />
        @endif
    </div>
</div>
