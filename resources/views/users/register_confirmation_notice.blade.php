@extends('layouts.core.register')

@section('title', trans('messages.create_your_account'))

@section('content')

    <div class="row mt-5">
        <div class="col-md-2"></div>
        <div class="col-md-2 text-end mt-60">
            <a class="main-logo-big" href="{{ action('HomeController@index') }}">
                @if (\Acelle\Model\Setting::get('site_logo_big'))
                    <img width="150px" src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_big')) }}" alt="">
                @else
                    <img width="150px" src="{{ URL::asset('images/logo_square.png') }}" alt="">
                @endif
            </a>
        </div>
        <div class="col-md-5">
            
            <h1 class="mb-10">{{ trans('messages.email_confirmation') }}</h1>
            <p>{!! trans('messages.activation_email_sent_content') !!}</p>
                
        </div>
        <div class="col-md-1"></div>
    </div>
@endsection
