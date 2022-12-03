@extends('layouts.core.backend')

@section('title', trans('messages.contact_information'))

@section('page_header')

	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
			<li class="breadcrumb-item "><a href="{{ action("Admin\AccountController@profile") }}">{{ trans('messages.profile') }}</a></li>
			<li class="breadcrumb-item active">{{ trans('messages.settings') }}</li>
		</ul>
		<h1>
			<span class="text-semibold"><i class="icon-address-book3"></i> {{ $contact->company }} ({{ $contact->name() }})</span>
		</h1>
	</div>

@endsection

@section('content')

	@include("admin.account._menu")

	<form enctype="multipart/form-data" action="{{ action('Admin\AccountController@contact') }}" method="POST" class="form-validate-jqueryz">
		{{ csrf_field() }}

		<h2 class="text-semibold text-primary">{{ trans('messages.primary_account_contact') }}</h2>

		<div class="row">
			<div class="col-md-6">

				<div class="row">
					<div class="col-md-6">
						@include('helpers.form_control', ['type' => 'text', 'name' => 'first_name', 'value' => $contact->first_name, 'rules' => Acelle\Model\Contact::$rules])
					</div>
					<div class="col-md-6">
						@include('helpers.form_control', ['type' => 'text', 'name' => 'last_name', 'value' => $contact->last_name, 'rules' => Acelle\Model\Contact::$rules])
					</div>
				</div>

				@include('helpers.form_control', ['type' => 'text', 'label' => trans('messages.email_at_work'), 'name' => 'email', 'value' => $contact->email, 'help_class' => 'admin_contact', 'rules' => Acelle\Model\Contact::$rules])

				@include('helpers.form_control', ['type' => 'text', 'name' => 'address_1', 'value' => $contact->address_1, 'rules' => Acelle\Model\Contact::$rules])

				<div class="row">
					<div class="col-md-6">
						@include('helpers.form_control', ['type' => 'text', 'name' => 'city', 'value' => $contact->city, 'rules' => Acelle\Model\Contact::$rules])
					</div>
					<div class="col-md-6">
						@include('helpers.form_control', ['type' => 'text', 'label' => trans('messages.zip_postal_code'), 'name' => 'zip', 'value' => $contact->zip, 'rules' => Acelle\Model\Contact::$rules])
					</div>
				</div>

				@include('helpers.form_control', ['type' => 'text', 'label' => trans('messages.website_url'), 'name' => 'url', 'value' => $contact->url, 'rules' => Acelle\Model\Contact::$rules])

			</div>
			<div class="col-md-6">

				@include('helpers.form_control', ['type' => 'text', 'label' => trans('messages.company_organization'), 'name' => 'company', 'value' => $contact->company, 'rules' => Acelle\Model\Contact::$rules])

				@include('helpers.form_control', ['type' => 'text', 'label' => trans('messages.office_phone'), 'name' => 'phone', 'value' => $contact->phone, 'rules' => Acelle\Model\Contact::$rules])

				@include('helpers.form_control', ['type' => 'text', 'name' => 'address_2', 'value' => $contact->address_2, 'rules' => Acelle\Model\Contact::$rules])

				@include('helpers.form_control', ['type' => 'text', 'label' => trans('messages.state_province_region'), 'name' => 'state', 'value' => $contact->state, 'rules' => Acelle\Model\Contact::$rules])

				@include('helpers.form_control', ['type' => 'select', 'name' => 'country_id', 'label' => trans('messages.country'), 'value' => $contact->country_id, 'options' => Acelle\Model\Country::getSelectOptions(), 'include_blank' => trans('messages.choose'), 'rules' => Acelle\Model\Contact::$rules])

			</div>
		</div>
		<hr>
		<div class="text-left">
			<button class="btn btn-secondary"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
		</div>

	<form>

@endsection
