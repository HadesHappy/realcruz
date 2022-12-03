@extends('layouts.core.frontend')

@section('title', trans('messages.sender.create'))
    
@section('page_header')
    
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("SenderController@index") }}">{{ trans('messages.verified_senders') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("SendingDomainController@index") }}">{{ trans('messages.email_addresses') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold">{{ trans('messages.verified_senders') }}</span>
        </h1> 
    </div>

@endsection

@section('content')
    @include('senders._menu')
    
    <h2>{{ trans('messages.sender.create') }}</h2>
        
    <form enctype="multipart/form-data" action="{{ action('SenderController@store') }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}
        
        @include('senders._form')
    <form>
                
@endsection
