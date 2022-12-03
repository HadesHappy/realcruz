@extends('layouts.core.backend')

@section('title', trans('messages.create_admin'))
	
@section('page_header')
	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li class="breadcrumb-item"><a href="{{ action("Admin\AdminController@index") }}">{{ trans('messages.admins') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_admin') }}</span>
				</h1>
			</div>

@endsection

@section('content')
          <form enctype="multipart/form-data" action="{{ action('Admin\AdminController@store') }}" method="POST" class="form-validate-jqueryz">
					{{ csrf_field() }}
					
					@include('admin.admins._form')			
					
				<form>
				
@endsection
