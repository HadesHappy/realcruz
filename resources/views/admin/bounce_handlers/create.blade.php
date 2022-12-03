@extends('layouts.core.backend')

@section('title', trans('messages.create_bounce_handler'))

@section('page_header')

			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li class="breadcrumb-item"><a href="{{ action("Admin\BounceHandlerController@index") }}">{{ trans('messages.bounce_handlers') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_bounce_handler') }}</span>
				</h1>
			</div>

@endsection

@section('content')
                <form action="{{ action('Admin\BounceHandlerController@store') }}" method="POST" class="form-validate-jquery">
					{{ csrf_field() }}

					@include('admin.bounce_handlers._form')
				<form>

@endsection
