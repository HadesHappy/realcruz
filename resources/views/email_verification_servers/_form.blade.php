<div class="row">
	<div class="col-md-4">
		@include('helpers.form_control', ['type' => 'text',
			'name' => 'name',
			'value' => $server->name,
			'class' => '',
			'help_class' => 'email_verification_server',
			'rules' => $server->rules()
		])
	</div>
	<div class="col-md-4">
		@include('helpers.form_control', ['type' => 'select',
			'name' => 'type',
			'value' => $server->type,
			'class' => 'hook',
			'label' => trans('messages.verification_server_type'),
			'options' => Acelle\Model\EmailVerificationServer::typeSelectOptions(),
			'include_blank' => trans('messages.choose'),
			'help_class' => 'email_verification_server',
			'rules' => $server->rules()
		])
	</div>
</div>

<div class="row">
	<div class="col-md-12 ajax-detail-box" data-url="{{ action('EmailVerificationServerController@options') }}" data-form=".email-verification-server-form">
		@include('email_verification_servers._options', [
			'options' => $options
		])
	</div>
</div>

<h4 class="text-semibold text-primary">{{ trans('messages.verification_server_credits') }}</h4>

<div class="row">
	<div class="col-md-12">
		<p>{!! trans('messages.verification_server_credits.wording') !!}</p>
	</div>
	<div class="col-md-4">
		@include('helpers.form_control', [
			'type' => 'text',
			'class' => 'numeric',
			'name' => 'options[limit_value]',
			'value' => isset($options['limit_value']) ? $options['limit_value'] : '',
			'label' => trans('messages.verification_limit_value'),
			'help_class' => 'email_verification_server',
			'rules' => $server->rules()
		])
	</div>
	<div class="col-md-4">
		@include('helpers.form_control', [
			'type' => 'text',
			'class' => 'numeric',
			'name' => 'options[limit_base]',
			'value' => isset($options['limit_base']) ? $options['limit_base'] : '',
			'label' => trans('messages.verification_limit_base'),
			'help_class' => 'email_verification_server',
			'rules' => $server->rules()
		])
	</div>
	<div class="col-md-4">
		@include('helpers.form_control', ['type' => 'select',
			'name' => 'options[limit_unit]',
			'value' => isset($options['limit_unit']) ? $options['limit_unit'] : '',
			'label' => trans('messages.verification_limit_unit'),
			'options' => Acelle\Model\EmailVerificationServer::quotaTimeUnitOptions(),
			'include_blank' => trans('messages.choose'),
			'help_class' => 'email_verification_server',
			'rules' => $server->rules()
		])
	</div>
</div>

<hr >
<div class="text-left">
	<button class="btn btn-primary me-1"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
	<a href="{{ action('EmailVerificationServerController@index') }}" role="button" class="btn btn-secondary">
		<i class="icon-cross2"></i> {{ trans('messages.cancel') }}
	</a>
</div>
