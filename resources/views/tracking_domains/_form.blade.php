{{ csrf_field() }}

<div class="row">
	<div class="col-sm-6 col-md-6">
		<label class="mb-3 font-weight-semibold">{{ trans('messages.domain_name') }}</label>
		<div class="tracking-domain-scheme-name">			
			@include('helpers.form_control', [
				'type' => 'select',
				'class' => '',
				'name' => 'scheme',
				'label' => '',
				'value' => $domain->scheme,
				'help_class' => 'tracking_domain',
				'options' => [
					['text' => 'HTTP', 'value' => 'http'],
					['text' => 'HTTPS', 'value' => 'https'],
				],
			])
			@include('helpers.form_control', [
				'type' => 'text',
				'class' => '',
				'name' => 'name',
				'label' => '',
				'value' => $domain->name,
				'help_class' => 'tracking_domain',
			])
		</div>
	</div>
	<div class="col-sm-6 col-md-6">
		<div class="form-group checkbox-right-switch">
			@include('helpers.form_control', [
				'type' => 'checkbox',
				'class' => '',
				'name' => 'dns_verification',
				'value' => 1,
				'help_class' => 'tracking_domain',
				'options' => [0, 1],
				'readonly' => true,
				'disabled' => true,
			])
		</div>
	</div>
</div>
<hr >
<div class="text-left">
	<button class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
</div>
