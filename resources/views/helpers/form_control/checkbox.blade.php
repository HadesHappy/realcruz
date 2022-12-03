<label class="form-label mb-0">
    <span class="form-label d-flex align-items-center">
        <label>
            <input
                type="checkbox"
                class="styled me-2 {{ isset($attributes) && isset($attributes['class']) ? $attributes['class'] : ''  }}"

                {{ isset($attributes) && isset($attributes['checked']) && $attributes['checked'] ? 'checked' : ''  }}

                name="{{ $name }}"
                value="{{ isset($value) ? $value : "" }}"

                @if (isset($attributes))
                    @foreach ($attributes as $k => $v)
                        @if (!in_array($k, ['class','checked']))
                            {{ $k }}="{{ $v }}"
                        @endif
                    @endforeach
                @endif
            >
        </label>
        @if (isset($label))
            
                <span class="ms-2">{{ $label }}</span>

        @endif
    </span>
</label>