<div class="row">
	<div class="col-md-6">
		<p>{{ trans('messages.sender.new.wording') }}</p>
			
		<div class="sub_section">
			@include('helpers.form_control', [
				'type' => 'text',
				'name' => 'name',
				'value' => $sender->name,
				'help_class' => 'sender',
				'rules' => $sender->rules()
			])

			@include('helpers.form_control', [
				'type' => 'text',
				'disabled' => isset($sender->id),
				'name' => 'email',
				'value' => $sender->email,
				'help_class' => 'sender',
				'rules' => $sender->rules()
			])

		</div>
		<div class="text-left">
			<button class="btn btn-secondary me-1"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
			<a href="{{ action("SenderController@index") }}" class="btn btn-secondary"><i class="icon-cross"></i> {{ trans('messages.cancel') }}</a>
		</div>
	</div>
</div>
