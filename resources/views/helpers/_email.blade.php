            <input
                {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }}
                id="{{ $name }}" placeholder="{{ isset($placeholder) ? $placeholder : "" }}"
                value="{{ isset($value) ? $value : "" }}"
                type="email"
                name="{{ $name }}"
                class="form-control{{ $classes }}  {{ isset($class) ? $class : "" }}"
                {!! isset($default_value) ? 'default-value="'.$default_value.'"' : '' !!}
                {{ isset($readonly) && $readonly ? "readonly=readonly" : "" }}
            >
