@extends('layouts.core.backend')

@section('title', $group->name)

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Admin\AdminGroup2Controller@index") }}">{{ trans('messages.user_groups') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">
edit
</span> {{ $group->name }}</span>
        </h1>
    </div>

@endsection

@section('content')

    <form action="{{ action('Admin\AdminGroup2Controller@update', $group->id) }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PATCH">

        @include("admin.admin_groups2._form")
        <hr />
        <div class="text-left">
            <button class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
            <a href="{{ action('Admin\AdminGroup2Controller@index') }}" class="btn btn-link"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
        </div>
    <form>

@endsection
