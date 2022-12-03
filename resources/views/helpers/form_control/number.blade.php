@if (isset($label))
    <label class="form-label">
        {{ $label }}
    </label>
@endif

<input
    type="number"
    class="form-control {{ isset($attributes) && isset($attributes['class']) ? $attributes['class'] : ''  }}"

    name="{{ $name }}"
    value="{{ isset($value) ? $value : "" }}""

    @if (isset($attributes))
        @foreach ($attributes as $k => $v)
            @if (!in_array($k, ['class']) && $k !== false)
                {{ $k }}="{{ $v }}"
            @endif
        @endforeach
    @endif
>

@if ($errors->has($name))
    <p class="mb-0 text-danger small mt-1">
        {{ $errors->first($name) }}
    </p>
@endif
