<div class="row condition-line" rel="__index__">
	<div class="col-md-4">
		<div class="form-group">
			<select class="select condition-field-select" name="conditions[__index__][field_id]">
				<optgroup label="{{ trans('messages.list_fields') }}">
					@foreach($list->getFields as $field)
						<option value="{{ $field->uid }}">{{ $field->label }}</option>
					@endforeach
				</optgroup>
				<optgroup label="{{ trans('messages.email_verification') }}">
					<option value="verification">{{ trans('messages.verification_result') }}</option>
				</optgroup>
				<optgroup label="{{ trans('messages.segment.other_cond') }}">
					<option value="tag">{{ trans('messages.segment.tag') }}</option>
				</optgroup>
				<optgroup label="{{ trans('messages.segment.activities') }}">
					<option value="last_open_email">{{ trans('messages.segment.last_open_email') }}</option>
					<option value="last_link_click">{{ trans('messages.segment.last_link_click') }}</option>
					<option value="created_date">{{ trans('messages.segment.created_date') }}</option>
				</optgroup>
			</select>
		</div>
	</div>
	<div class="col-md-7 operator_value_col" data-url="{{ action('SegmentController@conditionValueControl') }}">

	</div>
	<div class="col-md-1">
		<a onclick="$(this).parents('.condition-line').remove()" href="#delete" class="btn bg-danger-400"><span class="material-symbols-rounded">
delete_outline
</span></a>
	</div>
</div>
