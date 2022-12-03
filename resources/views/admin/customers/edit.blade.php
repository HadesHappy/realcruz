@extends('layouts.core.backend')

@section('title', $customer->user->displayName())
    
@section('page_header')
    
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Admin\CustomerController@index") }}">{{ trans('messages.customers') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('messages.update') }}</li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">
                            person_outline
                            </span> {{ $customer->user->displayName() }}</span>
        </h1>
    </div>
                
@endsection

@section('content')
    @include('admin.customers._tabs')

    <form enctype="multipart/form-data" action="{{ action('Admin\CustomerController@update', $customer->uid) }}" method="POST" class="form-validate-jquery">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PATCH">
        
        @include('admin.customers._form')
        
    <form>
@endsection
