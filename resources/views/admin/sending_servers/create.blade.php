@extends('layouts.core.backend')

@section('title', trans('messages.create_sending_server'))

@section('page_header')

	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Admin\SendingServerController@index") }}">{{ trans('messages.sending_servers') }}</a></li>
		</ul>
		<h1 class="mc-h1">
			<span class="text-semibold">{{ trans('messages.create_sending_server') }}</span>
		</h1>
	</div>

@endsection

@section('content')

    @include('admin.sending_servers.form.' . $server->type)
    
@endsection
