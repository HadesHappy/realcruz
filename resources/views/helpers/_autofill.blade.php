            <div {{ isset($id) ? "id=" . $id : 'class="autofill"' }}>
                <input
                    header="{{ isset($header) ? $header : "" }}"
                    empty-message="{{ isset($empty) ? $empty : "" }}"
                    error-message='{{ isset($error) ? $error : "" }}'
                    autocomplete="new-password"
                    {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }}
                    id="{{ $name }}" placeholder="{{ isset($placeholder) ? $placeholder : "" }}"
                    value="{{ isset($value) ? $value : "" }}"
                    data-url="{{ isset($url) ? $url : "" }}"
                    type="text"
                    name="{{ $name }}"
                    class="form-control{{ $classes }}  {{ isset($class) ? $class : "" }} autofill-input"
                    {!! isset($default_value) ? 'default-value="'.$default_value.'"' : '' !!}
                    {{ isset($readonly) && $readonly ? "readonly=readonly" : "" }}
                >
            </div>
