@extends('layouts.automation.frontend')

@section('title', $automation->name . ": " . trans('messages.subscribers'))

@section('page_header')

	<ul class="breadcrumb breadcrumb-caret position-right">
		<li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ action("Automation2Controller@index") }}">{{ trans('messages.automations') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ action("Automation2Controller@subscribers", $automation->uid) }}">{{ $automation->name }}</a></li>
	</ul>

	<h1>
		<span class="text-semibold">{{ $subscriber->email }}</span>
	</h1>
@endsection

@section('content')
@endsection
