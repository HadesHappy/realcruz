@extends('layouts.core.install')

@section('title', trans('messages.configuration'))

@section('content')

<form action="{{ action('InstallController@siteInfo') }}" method="POST" class="form-validate-jqueryz">
	{!! csrf_field() !!}
	
	<h3 class="text-primary"><span class="material-symbols-rounded">
		maps_home_work
		</span> {{ trans('messages.general') }}</h3>
    <div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'site_name',
                'value' => (isset($site_info["site_name"]) ? $site_info["site_name"] : ""),
                'help_class' => 'install',
                'rules' => ["site_name" => "required"]
            ])
		</div>
		<div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'site_keyword',
                'value' => (isset($site_info["site_keyword"]) ? $site_info["site_keyword"] : ""),
                'help_class' => 'install',
                'rules' => ["site_keyword" => "required"]
            ])
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'license',
				'label' => trans('messages.license_optional'),
                'value' => (isset($site_info["license"]) ? $site_info["license"] : ""),
                'help_class' => 'install',
                'rules' => []
            ])
            
        </div>
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'textarea',
                'name' => 'site_description',
                'value' => (isset($site_info["site_description"]) ? $site_info["site_description"] : ""),
                'help_class' => 'install',
                'rules' => ["site_description" => "required"]
            ])
        </div>
    </div>
	<hr />
	<h3 class="text-primary"><span class="material-symbols-rounded">
manage_accounts
</span> {{ trans('messages.admin_info') }}</h3>
	<div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'first_name',
                'value' => (isset($site_info["first_name"]) ? $site_info["first_name"] : ""),
                'help_class' => 'install',
                'rules' => $rules
            ])
        </div>
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'last_name',
                'value' => (isset($site_info["last_name"]) ? $site_info["last_name"] : ""),
                'help_class' => 'install',
                'rules' => $rules
            ])
        </div>
    </div>
        
    <div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'email',
                'value' => (isset($site_info["email"]) ? $site_info["email"] : ""),
                'help_class' => 'install',
                'rules' => $rules
            ])
        </div>
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'password',
                'name' => 'password',
                'value' => (isset($site_info["password"]) ? $site_info["password"] : ""),
				'help_class' => 'install',
				'eye' => true,
                'rules' => $rules
            ])      
        </div>
    </div>
			
    <div class="row">
        <div class="col-md-6">
			@include('helpers.form_control', ['type' => 'select', 'name' => 'timezone', 'value' => (isset($site_info["timezone"]) ? $site_info["timezone"] : ""), 'options' => Tool::getTimezoneSelectOptions(), 'include_blank' => trans('messages.choose'), 'rules' => $rules])
        </div>
		<div class="col-md-6">
			<div class="form-group checkbox-right-switch">
				@include('helpers.form_control', [
					'type' => 'checkbox',
					'name' => 'create_customer_account',
					'label' => trans('messages.create_customer_account'),
					'value' => (isset($site_info["create_customer_account"]) ? $site_info["create_customer_account"] : "yes"),
					'options' => ['no', 'yes'],
					'help_class' => 'admin',
					'rules' => $rules
				])
			</div>
        </div>
    </div>
	
	<hr />
	<h3 class="text-primary"><span class="material-symbols-rounded">
mark_email_read
</span> {{ trans('messages.system_email_configuration') }}</h3>
	<div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'select',
                'name' => 'mail_mailer',
								'label' => trans('messages.mail_mailer'),
                'value' => (isset($site_info["mail_mailer"]) ? $site_info["mail_mailer"] : ""),
								'options' => [["value" => "sendmail", "text" => trans('messages.sendmail')],["value" => "smtp", "text" => trans('messages.smtp')]],
                'help_class' => 'install',
                'rules' => $rules
            ])
				</div>
	</div>
		
	<div class="">
		<div class="row smtp_box mailer-setting smtp">
			<div class="col-md-6">
				@include('helpers.form_control', [
					'type' => 'text',
					'name' => 'smtp_hostname',
					'label' => trans('messages.hostname'),
					'value' => (isset($site_info["smtp_hostname"]) ? $site_info["smtp_hostname"] : ""),
					'help_class' => 'install',
					'rules' => $smtp_rules
				])
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-6">
						@include('helpers.form_control', [
							'type' => 'text',
							'name' => 'smtp_port',
							'label' => trans('messages.port'),
							'value' => (isset($site_info["smtp_port"]) ? $site_info["smtp_port"] : ""),
							'help_class' => 'install',
							'rules' => $smtp_rules
						])
					</div>
					<div class="col-md-6">
						@include('helpers.form_control', [
							'type' => 'text',
							'name' => 'smtp_encryption',
							'label' => trans('messages.encryption'),
							'value' => (isset($site_info["smtp_encryption"]) ? $site_info["smtp_encryption"] : ""),
							'help_class' => 'install',
							'rules' => $smtp_rules
						])
					</div>
				</div>
			</div>
		</div>
		<div class="row mailer-setting smtp">
			<div class="col-md-6">
				@include('helpers.form_control', [
					'type' => 'text',
					'name' => 'smtp_username',
					'label' => trans('messages.username'),
					'value' => (isset($site_info["smtp_username"]) ? $site_info["smtp_username"] : ""),
					'help_class' => 'install',
					'rules' => $smtp_rules
				])
			</div>
			<div class="col-md-6">
				@include('helpers.form_control', [
					'type' => 'password',
					'name' => 'smtp_password',
					'label' => trans('messages.password'),
					'value' => (isset($site_info["smtp_password"]) ? $site_info["smtp_password"] : ""),
					'help_class' => 'install',
					'eye' => true,
					'rules' => $smtp_rules
				])
			</div>
		</div>
		<div class="row mailer-setting smtp sendmail">
			<div class="col-md-6">
				@include('helpers.form_control', [
					'type' => 'text',
					'name' => 'mail_from_email',
					'label' => trans('messages.from_email'),
					'value' => (isset($site_info["mail_from_email"]) ? $site_info["mail_from_email"] : ""),
					'help_class' => 'install',
					'rules' => $smtp_rules
				])
			</div>
			<div class="col-md-6">
				@include('helpers.form_control', [
					'type' => 'text',
					'name' => 'mail_from_name',
					'label' => trans('messages.from_name'),
					'value' => (isset($site_info["mail_from_name"]) ? $site_info["mail_from_name"] : ""),
					'help_class' => 'install',
					'rules' => $smtp_rules
				])
			</div>
			<div class="col-md-6 mailer-setting sendmail">
				@include('helpers.form_control', [
					'type' => 'text',
					'name' => 'sendmail_path',
					'label' => trans('messages.sendmail_path'),
					'value' => (isset($site_info["sendmail_path"]) ? $site_info["sendmail_path"] : "/usr/sbin/sendmail"),
					'help_class' => 'env',
					'rules' => ['mailer.sendmail_path' => 'required']
				])  
			</div>
		</div>
	</div>
	
	<hr >
	<div class="text-end">                                    
		<button data-wait="{{ trans('messages.button_processing') }}" type="submit" class="btn btn-primary bg-teal save-button">{!! trans('messages.next') !!} <span class="material-symbols-rounded">
east
</span></button>
	</div>
	
</form>
<script>

	$(function() {
		$('.save-button').on('click', function() {
			addButtonMask($(this));
		});
	});

	function toogleMailer() {
		var value = $("select[name='mail_mailer']").val();
		$('.mailer-setting').hide();
        $('.mailer-setting.' + value).show();
	}
	$(document).ready(function() {	
		toogleMailer();
		$("select[name='mail_mailer']").change(function() {
			toogleMailer();
		});
	});
</script>
	
@endsection
