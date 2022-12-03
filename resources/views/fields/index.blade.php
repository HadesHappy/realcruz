@extends('layouts.core.frontend')

@section('title', $list->name . ": " . trans('messages.manage_list_fields') )

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/datetime/anytime.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.date.js') }}"></script>
@endsection

@section('page_header')

			@include("lists._header")

@endsection

@section('content')

	@include("lists._menu")

	<h2 class="text-primary my-4"><span class="material-symbols-rounded">
fact_check
</span> {{ trans('messages.manage_list_fields') }}</h2>

	<p>{!! trans('messages.fields_intro') !!}</p>

	@if ($errors->has("miss_main_field_tag"))
		<div class="text-danger text-semibold">
			<strong>{{ $errors->first("miss_main_field_tag") }}</strong>
		</div>
	@endif
	@if ($errors->has("conflict_field_tags"))
		<div class="text-danger text-semibold">
			<strong>{{ $errors->first("conflict_field_tags") }}</strong>
		</div>
	@endif

	<form action="{{ action('FieldController@store', $list->uid) }}" class="listing-form"
		sort-urla="{{ action('FieldController@sort', $list->uid) }}"
		per-page="1"
		method="POST"
	>
		{{ csrf_field() }}

		@if ($fields->count() > 0)
			<table class="table table-box table-box-head field-list"
				current-page="1"
			>
					<th width="1%"></th>
					<th>{{ trans('messages.field_label_and_type') }}</th>
					<th width="100px">{{ trans('messages.required?') }}</th>
					<th width="100px">{{ trans('messages.visible?') }}</th>
					<th>{{ trans('messages.tag') }}</th>
					<th>{{ trans('messages.default_value') }}</th>
					<th></th>
				@foreach ($fields as $key => $item)
					<tr class="draggable" rel="{{ $item->uid }}">
						<td>
							<input type="hidden" name="fields[{{ $item->uid }}][uid]" value="{{ $item->uid }}" />
							<i data-action="move" class="icon icon-more2 list-drag-button"></i>
						</td>
						<td class="text-nowrap" style="width: 28%">
							@include('helpers.form_control', ['type' => 'text', 'name' => 'fields[' . $item->uid . '][label]', 'label' => '', 'subfix' => trans('messages.' . $item->type), 'value' => $item->label, 'help_class' => 'field'])
							<input type="hidden"
								value="{{ $item->type }}"
								name="fields[{{ $item->uid }}][type]"
							/>
						</td>
						<td class="text-nowrap">
							@include('helpers.form_control', ['disabled' => $item->tag == 'EMAIL', 'type' => 'checkbox', 'name' => 'fields[' . $item->uid . '][required]', 'label' => '', 'value' => $item->required, 'options' => [false,true], 'help_class' => 'field'])
						</td>
						<td class="text-nowrap">
							@include('helpers.form_control', ['disabled' => $item->tag == 'EMAIL', 'type' => 'checkbox', 'name' => 'fields[' . $item->uid . '][visible]', 'label' => '', 'value' => $item->visible, 'options' => [false,true], 'help_class' => 'field'])
						</td>
						<td class="text-nowrap">
							<div class="d-flex align-items-center">
								<span>{SUBSCRIBER_</span>
									@include('helpers.form_control', [
									'disabled' => $item->tag == 'EMAIL',
									'type' => 'text',
									'name' => 'fields[' . $item->uid . '][tag]',
									'label' => '',
									'value' => $item->tag,
									'help_class' => 'field',
									])
								<span>}</span>
							</div>
								
						</td>
						<td class="text-nowrap">
							@include('helpers.form_control', ['type' => Acelle\Model\Field::getControlNameByType($item->type), 'name' => 'fields[' . $item->uid . '][default_value]', 'label' => '', 'value' => $item->default_value, 'help_class' => 'field'])
						</td>
						<td>
							@if ($item->tag != 'EMAIL')
								@if (is_object(Acelle\Model\Field::findByUid($item->uid)))
									<a no-ajax="true" href="{{ action('FieldController@delete', ['list_uid' => $list->uid, 'uid' => $item->uid]) }}" link-confirm="{!! trans('messages.delete_field_alert') !!}" class="btn bg-danger-400 remove-field-button">
										<span class="material-symbols-rounded">
delete_outline
</span>
									</a>
								@else
									<a href="#delete" class="btn bg-danger-400 remove-not-saved-field"><span class="material-symbols-rounded">
delete_outline
</span></a>
								@endif
							@endif
						</td>
					</tr>

					@if (count($item->fieldOptions))
						<tr class="child" parent="{{ $item->uid }}">
							<td></td>
							<td colspan="3" class="sub_field_options">
								<div class="row">
									<div class="col-md-12">
										<div class="row label-value-groups">
											@foreach ($item->fieldOptions as $key => $option)
												<div class="col-md-6 text-nowrap label-value-group" rel="{{ $option->uid }}">
													<div class="pull-left me-2">@include('helpers.form_control', ['type' => 'text', 'placeholder' => trans('messages.label'), 'name' => 'fields[' . $item->uid . '][options][' . $option->uid . '][label]', 'label' => '', 'value' => $option->label, 'help_class' => 'field'])</div>
													<div class="pull-left me-2">@include('helpers.form_control', ['type' => 'text', 'placeholder' => trans('messages.value'), 'name' => 'fields[' . $item->uid . '][options][' . $option->uid . '][value]', 'label' => '', 'value' => $option->value, 'help_class' => 'field'])</div>
													<div class="pull-left"><a href="#remove" onclick="$(this).parents('.label-value-group').remove()" class="btn btn-xs bg-grey-600"><i class="icon-cross"></i></a></div>
												</div>
											@endforeach
										</div>
									</div>
								</div>
							</td>
							<td colspan="3">
								<a href="#add_more" class="btn btn-secondary add_label_value_group">{{ trans('messages.add_more') }}</a>
							</td>
						</tr>
					@endif
				@endforeach
			</table>
		@endif
		<br />
		<h4>{{ trans('messages.add_field') }}</h4>
		<div>
			<span sample-url="{{ action("FieldController@sample", ['list_uid' => $list->uid, "type" => "text"]) }}" class="btn btn-default btn-xs add-custom-field-button me-2" type_name="text">
				<i class="material-symbols-rounded">drag_handle</i> {{ trans('messages.text_field') }}
			</span>
			<span sample-url="{{ action("FieldController@sample", ['list_uid' => $list->uid, "type" => "number"]) }}" class="btn btn-default btn-xs add-custom-field-button me-2" type_name="number">
				<i class="material-symbols-rounded">pin</i> {{ trans('messages.number_field') }}
			</span>
			<span sample-url="{{ action("FieldController@sample", ['list_uid' => $list->uid, "type" => "dropdown"]) }}" class="btn btn-default btn-xs add-custom-field-button me-2" type_name="dropdown">
				<i class="material-symbols-rounded">playlist_add_check</i> {{ trans('messages.dropdown_field') }}
			</span>
			<span sample-url="{{ action("FieldController@sample", ['list_uid' => $list->uid, "type" => "multiselect"]) }}" class="btn btn-default btn-xs add-custom-field-button me-2" type_name="multiselect"><i class="material-symbols-rounded">checklist_rtl</i> {{ trans('messages.multiselect_field') }}</span>
			<span sample-url="{{ action("FieldController@sample", ['list_uid' => $list->uid, "type" => "checkbox"]) }}" class="btn btn-default btn-xs add-custom-field-button me-2" type_name="checkbox"><i class="material-symbols-rounded">select_check_box</i> {{ trans('messages.checkbox_field') }}</span>
			<span sample-url="{{ action("FieldController@sample", ['list_uid' => $list->uid, "type" => "radio"]) }}" class="btn btn-default btn-xs add-custom-field-button me-2" type_name="radio"><i class="material-symbols-rounded">check_circle</i> {{ trans('messages.radio_field') }}</span>
			<span sample-url="{{ action("FieldController@sample", ['list_uid' => $list->uid, "type" => "date"]) }}" class="btn btn-default btn-xs add-custom-field-button me-2" type_name="date"><i class="material-symbols-rounded">calendar_month</i> {{ trans('messages.date_field') }}</span>
			<span sample-url="{{ action("FieldController@sample", ['list_uid' => $list->uid, "type" => "datetime"]) }}" class="btn btn-default btn-xs add-custom-field-button me-2" type_name="datetime"><span class="material-symbols-rounded">
alarm
</span> {{ trans('messages.datetime_field') }}</span>
			<span sample-url="{{ action("FieldController@sample", ['list_uid' => $list->uid, "type" => "textarea"]) }}" class="btn btn-default btn-xs add-custom-field-button" type_name="textarea"><i class="material-symbols-rounded">article</i> {{ trans('messages.textarea_field') }}</span>
		</div>

		<hr /><br />
		<div class="">
			<button class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.save_change') }}</button>
		</div>
	</form>

	<script>
		$(function() {
			// List fields
			// ------------------------------
			// Change item per page
			$(document).on("click", ".add-custom-field-button", function(e) {
				var type_name = $(this).attr("type_name");
				var sample = $("."+type_name+"_sample ");
				var sample_url = $(this).attr("sample-url");

				// ajax update custom sort
				$.ajax({
					method: "GET",
					url: sample_url,
					data: {
						type: type_name,
					}
				})
				.done(function( msg ) {
					var index = $('.field-list tr').length;

					msg = msg.replace(/__index__/g, index);
					msg = msg.replace(/__type__/g, type_name);

					$('.field-list').append($('<div>').html(msg).find("table tbody").html());

					initJs($('.field-list tr').last());
				});
			});
			$(document).on("click", ".remove-not-saved-field", function(e) {
				$('tr[parent="'+$(this).parents('tr').attr('rel')+'"]').remove();
				$(this).parents('tr').remove();
			});
			$(document).on("click", ".add_label_value_group", function(e) {
				var last_item = $(this).parents("tr").find(".label-value-groups .label-value-group").last();
				var pre = last_item.attr("rel");
				var num = parseInt(pre)+1;
				var clone = $('<div>').append(last_item.clone()).html();

				clone = clone.replace('rel="'+pre+'"', 'rel="'+num+'"');
				clone = clone.replace('[options]['+pre+'][', '[options]['+num+'][');
				clone = clone.replace('[options]['+pre+'][', '[options]['+num+'][');
				clone = clone.replace('[options]['+pre+'][', '[options]['+num+'][');
				clone = clone.replace('[options]['+pre+'][', '[options]['+num+'][');
				clone = clone.replace('[options]['+pre+'][', '[options]['+num+'][');
				clone = clone.replace('[options]['+pre+'][', '[options]['+num+'][');
				clone = clone.replace('[options]['+pre+'][', '[options]['+num+'][');

				$(this).parents("tr").find(".label-value-groups").append(clone);
				$(this).parents("tr").find(".label-value-groups .label-value-group").last().find("input").val("");
				$(this).parents("tr").find(".label-value-groups .label-value-group").last().find(".help-block").remove();
				$(this).parents("tr").find(".label-value-groups .label-value-group").last().find(".form-group").removeClass("has-error");
			});
		})
	</script>
@endsection
