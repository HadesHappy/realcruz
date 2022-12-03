@extends('layouts.core.frontend')

@section('title', trans('messages.tracking_domain.create'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("TrackingDomainController@index") }}">{{ trans('messages.tracking_domains') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold">{{ trans('messages.tracking_domain.create') }}</span>
        </h1>         
    </div>

@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-10 col-lg-10">
            <p>{!! trans('messages.tracking_domain.wording') !!}</p>
        </div>
    </div>

    <form action="{{ action('TrackingDomainController@store') }}" method="POST" class="form-validate-jqueryz">
        @include('tracking_domains._form')
	</form>

@endsection
