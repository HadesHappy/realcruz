@extends('layouts.core.frontend')

@section('title', trans('messages.create_sending_domain'))

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
add
</span> {{ trans('messages.create_sending_domain') }}</span>
    </h1>

    <div class="row">
        <div class="col-sm-12 col-md-10 col-lg-10">
            <p>{!! trans('messages.sending_domain.wording') !!}</p>
        </div>
    </div>

    <form action="{{ action('SendingDomainController@store') }}" method="POST" class="form-validate-jqueryz">
        @include('sending_domains._form')
	</form>

@endsection
