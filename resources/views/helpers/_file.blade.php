<input
@if (isset($attributes))
    @foreach ($attributes as $k => $v)
        @if (!in_array($k, ['class']))
            {{ $k }}="{{ $v }}"
        @endif
    @endforeach
@endif

{{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }} id="{{ $name }}" placeholder="{{ isset($placeholder) ? $placeholder : "" }}" value="{{ isset($value) ? $value : "" }}" type="file" name="{{ $name }}" class="form-control file-styled-primary{{ $classes }} {{ isset($required) ? " required" : "" }}" />