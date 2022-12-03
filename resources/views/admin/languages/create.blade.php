@extends('layouts.core.backend')

@section('title', trans('messages.create_language'))
	
@section('page_header')
	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_language') }}</span>
				</h1>
			</div>

@endsection

@section('content')
                <form action="{{ action('Admin\LanguageController@store') }}" method="POST" class="form-validate-jqueryz">
					{{ csrf_field() }}
					
					@include('admin.languages._form')
				<form>
				
@endsection
