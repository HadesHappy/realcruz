@extends('layouts.core.backend')

@section('title', $server->name)
	
@section('page_header')
	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li class="breadcrumb-item"><a href="{{ action("Admin\BounceHandlerController@index") }}">{{ trans('messages.bounce_handlers') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><span class="material-symbols-rounded">
edit
</span> {{ $server->name }}</span>
				</h1>
			</div>
				
@endsection

@section('content')
	
				<form enctype="multipart/form-data" action="{{ action('Admin\BounceHandlerController@update', $server->uid) }}" method="POST" class="form-validate-jquery">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="PATCH">
					
					@include('admin.bounce_handlers._form')
					
				<form>
	
@endsection