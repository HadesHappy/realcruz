<div class="d-flex">
	
		@if (!empty($label)) <div style="width:100%"><label>
			
				<span class="fw-600">{!! $label !!}</span>
			

				@if (isset($help_class) && Lang::has('messages.' . $help_class . '.' . $name . '.' . $value . '.help'))
					<span class="checkbox-description">
						{!! trans('messages.' . $help_class . '.' . $name . '.' . $value . '.help') !!}
					</span>
				@endif

			</label></div>
		@endif
	
	<div class="d-flex align-items-top">
		<label><input
			{{ isset($checked) && $checked ? "checked" : "" }}
			{{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }}
			type="radio" name="{{ $name }}" value="{{ $value }}"
			class="styled {{ $classes }} {{ isset($class) ? $class : "" }}" data-on-text="On" data-off-text="Off" data-on-color="success" data-off-color="default"></label>
	</div>
</div>
	


