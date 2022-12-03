@extends('layouts.core.backend')

@section('title', trans('messages.system_logs'))

@section('page_header')

			<div class="page-title">				
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
				</ul>
				<h1>
					<span class="text-gear"><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.system_logs') }}</span>
				</h1>				
			</div>

@endsection

@section('content')
      <h2 class="text-semibold text-primary mt-0">{{ trans('messages.last_300_logs') }}</h2>
			<textarea class="system_logs">{{ $error_logs }}</textarea>
@endsection
