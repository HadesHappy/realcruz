@extends('layouts.core.backend')

@section('title', trans('messages.settings'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-gear"><span class="material-symbols-rounded">
                alarm
</span> {{ trans('messages.background_job') }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <div class="tabbable">

        @include("admin.settings._tabs")

        <form action="{{ action('Admin\SettingController@cronjob') }}" method="POST" class="form-validate-jqueryz">
            {!! csrf_field() !!}

            @include('elements._cron_jobs', ['show_all' => true])

            <hr>
            <div class="text-left">
                <button class="btn btn-primary bg-teal">
                    {!! trans('messages.save') !!}
                </button>
            </div>
        </form>
    </div>
@endsection
