<select name="{{ $name }}"
	{{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }}
	@if(isset($placeholder))
		data-placeholder="{{ $placeholder }}"
	@endif
		class="select-tag select-search{{ $classes }} {{ isset($class) ? $class : "" }} {{ isset($required) && !empty($required) ? 'required' : '' }}" {{ isset($multiple) && $multiple == true ? "multiple='multiple'" : "" }}>
	@if (isset($include_blank))
		<option value="">{{ $include_blank }}</option>
	@endif
	@foreach($options as $option)
		<option
			@if (is_array($value))
				{{ in_array($option['value'], $value) ? " selected" : "" }}
			@else
				{{ in_array($option['value'], explode(",", $value)) ? " selected" : "" }}
			@endif
			value="{{ $option['value'] }}"
		>{{ htmlspecialchars($option['text']) }}</option>
	@endforeach
</select>
