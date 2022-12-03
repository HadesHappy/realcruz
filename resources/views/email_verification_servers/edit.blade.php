@extends('layouts.core.frontend')

@section('title', $server->name)

@section('page_header')

            <div class="page-title">
                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ action("EmailVerificationServerController@index") }}">{{ trans('messages.email_verification_servers') }}</a></li>
                </ul>
                <h1>
                    <span class="text-semibold"><span class="material-symbols-rounded">
edit
</span> {{ $server->name }}</span>
                </h1>
            </div>

@endsection

@section('content')

    <form enctype="multipart/form-data" action="{{ action('EmailVerificationServerController@update', $server->uid) }}" method="POST" class="form-validate-jqueryz email-verification-server-form">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PATCH">
        @include('email_verification_servers._form')
    <form>

@endsection
