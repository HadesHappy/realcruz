<div class="text_sample">
	<table>
		<tr rel="__index__">
			<td>
				<input type="hidden"
					value="__index__"
					name="fields[__index__][uid]"
				/>
				<i data-action="move" class="icon icon-more2 list-drag-button"></i>
			</td>
			<td class="text-nowrap">                                        
				@include('helpers.form_control', ['type' => 'text', 'name' => 'fields[__index__][label]', 'label' => '', 'subfix' => trans('messages.'.$type), 'value' => '', 'help_class' => 'field'])
				<input type="hidden"
					value="{{ $type }}"
					name="fields[__index__][type]"
				/>
			</td>
			<td class="text-nowrap">
				@include('helpers.form_control', ['type' => 'checkbox', 'name' => 'fields[__index__][required]', 'label' => '', 'value' => '', 'options' => [false,true], 'help_class' => 'field'])
			</td>
			<td class="text-nowrap">
				@include('helpers.form_control', ['type' => 'checkbox', 'name' => 'fields[__index__][visible]', 'label' => '', 'value' => true, 'options' => [false,true], 'help_class' => 'field'])
			</td>
			<td class="text-nowrap">
				<div class="d-flex align-items-center">
					<span>{SUBSCRIBER_</span>
						@include('helpers.form_control', [
							'type' => 'text',
							'name' => 'fields[__index__][tag]',
							'label' => '', 'value' => '',
							'help_class' => 'field',
						])
					<span>}</span>
				</div>
				
			</td>
			<td class="text-nowrap">
				@include('helpers.form_control', ['type' => Acelle\Model\Field::getControlNameByType($type),'name' => 'fields[__index__][default_value]', 'label' => '', 'value' => '', 'help_class' => 'field'])
			</td>
			<td>
				<a href="#delete" class="btn bg-danger-400 remove-not-saved-field"><span class="material-symbols-rounded">
delete_outline
</span></a>
			</td>
		</tr>
			
		@if (in_array($type, ["dropdown","multiselect","checkbox","radio"]))
			<tr class="child" parent="__index__">
				<td></td>
				<td colspan="3" class="sub_field_options" width="50%">
					<div class="row">
						<div class="col-md-12">
							<div class="row label-value-groups">
								<div class="col-md-6 text-nowrap label-value-group" rel="0">
									<div class="pull-left me-2">@include('helpers.form_control', ['type' => 'text', 'placeholder' => trans('messages.label'), 'name' => 'fields[__index__][options][0][label]', 'label' => '', 'value' => '', 'help_class' => 'field'])</div>
									<div class="pull-left me-2">@include('helpers.form_control', ['type' => 'text', 'placeholder' => trans('messages.value'), 'name' => 'fields[__index__][options][0][value]', 'label' => '', 'value' => '', 'help_class' => 'field'])</div>
									<div class="pull-left"><a href="#remove" onclick="$(this).parents('.label-value-group').remove()" class="btn btn-xs bg-grey-600"><i class="icon-cross"></i></a></div>
								</div>
							</div>
						</div>
					</div>
				</td>
				<td colspan="3">
					<a href="#add_more" class="btn btn-secondary add_label_value_group">{{ trans('messages.add_more') }}</a>
				</td>
			</tr>
		@endif
			
	</table>
</div>
