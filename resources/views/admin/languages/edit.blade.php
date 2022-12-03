@extends('layouts.core.backend')

@section('title', $language->name)
	
@section('page_header')
	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><span class="material-symbols-rounded">
edit
</span> {{ $language->name }}</span>
				</h1>
			</div>
				
@endsection

@section('content')
	
				<form enctype="multipart/form-data" action="{{ action('Admin\LanguageController@update', $language->uid) }}" method="POST" class="form-validate-jquery">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="PATCH">
					
					@include('admin.languages._form')
					
				<form>
	
@endsection