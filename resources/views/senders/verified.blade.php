@extends('layouts.core.register')

@section('title', trans('messages.create_your_account'))

@section('content')

    <div class="row mt-5 mc-form">
        <div class="col-md-3"></div>
        <div class="col-md-6">

            <h1 class="mb-20">{{ trans('messages.sender.status.done.title') }}</h1>
            <p>{!! trans('messages.sender.status.done.description', ['email' => $sender->email ]) !!}</p>

            <!--
            <p><img width="100%" style="box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.19)" src="https://acellemail.s3.amazonaws.com/sample-verified-identity.png"></p>
            -->
            <hr>
            <div class="row flex align-items">
                <div class="col-md-4">
                    <form method="GET" action="{{ action('SenderController@index') }}">
                        <button type='submit' class="btn btn-secondary res-button"><i class="icon-check"></i> {{ trans('messages.sender.status.done.gotit') }}</button>
                    </form>
                </div>
                <div class="col-md-8" style="font-size:13px;font-style: italic">
                    {{ trans('messages.sender.status.done.note') }}
                </div>

            </div>
        </div>
        <div class="col-md-1"></div>
    </div>

@endsection
