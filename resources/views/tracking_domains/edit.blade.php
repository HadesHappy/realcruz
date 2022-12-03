@extends('layouts.core.frontend')

@section('title', $domain->name)

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("TrackingDomainController@index") }}">{{ trans('messages.tracking_domains') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold">{{ $domain->name }}</span>
        </h1>       
    </div>

@endsection

@section('content')
    <h2>
        <span class="text-semibold"><span class="material-symbols-rounded">
edit
</span> {{ $domain->name }}</span>
    </h2>

    <div class="row">
        <div class="col-sm-12 col-md-10 col-lg-10">
            <p>{!! trans('messages.tracking_domain.wording') !!}</p>
        </div>
    </div>

    <form enctype="multipart/form-data" action="{{ action('TrackingDomainController@update', $domain->uid) }}" method="POST" class="form-validate-jqueryz">
        <input type="hidden" name="_method" value="PATCH">
        @include('tracking_domains._form')
    </form>

@endsection
