@extends('layouts.core.backend')

@section('title', $currency->name)
	
@section('page_header')
	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li class="breadcrumb-item"><a href="{{ action("Admin\CurrencyController@index") }}">{{ trans('messages.currencies') }}</a></li>
					<li class="breadcrumb-item active">{{ trans('messages.update') }}</li>
				</ul>
				<h1>
					<span class="text-semibold"><span class="material-symbols-rounded">
                            person_outline
                            </span> {{ $currency->name }}</span>
				</h1>
			</div>
				
@endsection

@section('content')
	
				<form enctype="multipart/form-data" action="{{ action('Admin\CurrencyController@update', $currency->uid) }}" method="POST" class="form-validate-jqueryx">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="PATCH">
					
					@include('admin.currencies._form')
					
				<form>
	
@endsection