@extends('layouts.core.backend')

@section('title', trans('messages.create_email_verification_server'))

@section('page_header')

	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
			<li class="breadcrumb-item"><a href="{{ action("Admin\EmailVerificationServerController@index") }}">{{ trans('messages.email_verification_servers') }}</a></li>
		</ul>
		<h1>
			<span class="text-semibold"><span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_email_verification_server') }}</span>
		</h1>
	</div>

@endsection

@section('content')

	<form action="{{ action('Admin\EmailVerificationServerController@store', ["type" => request()->type]) }}" method="POST" class="form-validate-jqueryz email-verification-server-form">
		{{ csrf_field() }}

		@include('admin.email_verification_servers._form')
	<form>

@endsection
