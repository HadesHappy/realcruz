<select
    placeholder="{{ isset($placeholder) ? $placeholder : "" }}"
    class="select2-ajax {{ isset($class) ? $class : "" }}"
    name="{{ $name }}"
    data-url="{{ isset($url) ? $url : "" }}">
    @if (isset($include_blank))
		<option value="">{{ $include_blank }}</option>
	@endif
    @if (isset($selected) && isset($selected['value']))
        <option selected='selected' value="{{ $selected['value'] }}">{{ htmlspecialchars($selected['text']) }}</option>
    @endif
</select>