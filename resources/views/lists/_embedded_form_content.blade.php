@if ($list->getEmbeddedFormOption('stylesheet') == 'yes')
	<link href="{{ URL::asset('core/css/embedded.css') }}" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
@endif

@if (!empty($list->getEmbeddedFormOption('custom_css')))
	<style>{{ $list->getEmbeddedFormOption('custom_css') }}</style>
@endif

<div class="subscribe-embedded-form">
	@if (!empty($list->getEmbeddedFormOption('form_title')))
		<h2>{{ $list->getEmbeddedFormOption('form_title') }}</h2>
	@endif

	<p class="text-sm text-end"><span class="text-danger">*</span> {{ trans('messages.indicates_required') }}</p>
		@if (!isset($preview))
			<form action="{{ action('MailListController@embeddedFormSubscribe', $list->uid) }}" method="POST" class="form-validate-jqueryz">
		@endif

			@foreach ($list->getFields as $field)
				@if ($field->visible || $list->getEmbeddedFormOption('show_invisible') == 'yes')
					@if(($list->getEmbeddedFormOption('only_required_fields') == 'yes' && $field->required) || $list->getEmbeddedFormOption('only_required_fields') == 'no')
						@if ($field->type == "text")
							@include('helpers.form_control', ['type' => $field->type, 'name' => $field->tag, 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'rules' => $list->getFieldRules()])
						@elseif ($field->type == "number")
							@include('helpers.form_control', ['type' => 'number', 'name' => $field->tag, 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'rules' => $list->getFieldRules()])
						@elseif ($field->type == "textarea")
							@include('helpers.form_control', ['type' => 'textarea', 'name' => $field->tag, 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'rules' => $list->getFieldRules()])
						@elseif ($field->type == "dropdown")
							@include('helpers.form_control', ['type' => 'select', 'class' => 'form-control', 'name' => $field->tag, 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'options' => $field->getSelectOptions(), 'rules' => $list->getFieldRules()])
						@elseif ($field->type == "multiselect")
							@include('helpers.form_control', ['multiple' => true, 'class' => 'form-control', 'type' => 'select', 'name' => $field->tag . "[]", 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'options' => $field->getSelectOptions(), 'rules' => $list->getFieldRules()])
						@elseif ($field->type == "checkbox")
							@include('helpers.form_control', ['multiple' => true, 'type' => 'checkboxes', 'name' => $field->tag . "[]", 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'options' => $field->getSelectOptions(), 'rules' => $list->getFieldRules()])
						@elseif ($field->type == "radio")
							@include('helpers.form_control', ['multiple' => true, 'type' => 'radio', 'name' => $field->tag, 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'options' => $field->getSelectOptions(), 'rules' => $list->getFieldRules()])
						@elseif ($field->type == "date")
							@include('helpers.form_control', ['multiple' => true, 'type' => 'date', 'name' => $field->tag . "[]", 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'options' => $field->getSelectOptions(), 'rules' => $list->getFieldRules()])
						@elseif ($field->type == "datetime")
							@include('helpers.form_control', ['multiple' => true, 'type' => 'datetime', 'name' => $field->tag . "[]", 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'options' => $field->getSelectOptions(), 'rules' => $list->getFieldRules()])
						@endif
					@endif
				@endif
			@endforeach

			@if ($list->getEmbeddedFormOption('redirect_url'))
				<input type="hidden" name="redirect_url" value="{{ $list->getEmbeddedFormOption('redirect_url') }}" />
			@endif

			<div class="form-button">
			  <button class="btn btn-primary">{{ trans('messages.subscribe') }}</button>
			</div>

		@if (!isset($preview))
			<form>
		@endif

        </div>

		@if ($list->getEmbeddedFormOption('stylesheet') == 'yes')
			<link href="{{ URL::asset('core/css/app.css') }}?v={{ app_version() }}" rel="stylesheet" type="text/css">
		@endif
		
		@if ($list->getEmbeddedFormOption('javascript') == 'yes')
			<script type="text/javascript" src="{{ URL::asset('core/js/jquery-3.6.0.min.js') }}"></script>
			<script type="text/javascript" src="{{ URL::asset('core/validate/jquery.validate.min.js') }}"></script>
				
			<script type="text/javascript" src="{{ URL::asset('core/datetime/anytime.min.js') }}"></script>
			<script type="text/javascript" src="{{ URL::asset('core/datetime/moment.min.js') }}"></script>
			<script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.js') }}"></script>
			<script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.date.js') }}"></script>
		@endif
		
		
		@if ($list->getEmbeddedFormOption('javascript') == 'yes')
			<script type="text/javascript" src="{{ URL::asset('core/js/functions.js') }}"></script>
			@include('layouts.core._script_vars')
			<script>
				jQuery( document ).ready(function( $ ) {				
					@if (!isset($preview))
						@if ($list->getEmbeddedFormOption('javascript') == 'yes')			
							$(".subscribe-embedded-form form").validate({
								rules: {
								EMAIL: {
									required: true,
									email: true,
									remote: "{{ action('MailListController@checkEmail', $list->uid) }}"
								}
								}
							});
						@endif
					@endif

					initJs($('.subscribe-embedded-form'));
				});
			</script>
		@endif
			
		
