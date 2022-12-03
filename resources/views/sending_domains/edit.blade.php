@extends('layouts.core.frontend')

@section('title', $server->name)

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("SenderController@index") }}">{{ trans('messages.verified_senders') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("SendingDomainController@index") }}">{{ trans('messages.domains') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold">{{ trans('messages.verified_senders') }}</span>
        </h1>    
    </div>

@endsection

@section('content')
    
    @include('senders._menu')
    
    <h2>
        <span class="text-semibold"><span class="material-symbols-rounded">
edit
</span> {{ $server->name }}</span>
    </h2>

    <div class="row">
        <div class="col-sm-12 col-md-10 col-lg-10">
            <p>{!! trans('messages.sending_domain.wording') !!}</p>
        </div>
    </div>

    <form enctype="multipart/form-data" action="{{ action('SendingDomainController@update', $server->uid) }}" method="POST" class="form-validate-jqueryz">
        <input type="hidden" name="_method" value="PATCH">
        @include('sending_domains._form')
    </form>

@endsection
