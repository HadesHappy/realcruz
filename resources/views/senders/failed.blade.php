@extends('layouts.core.register')

@section('title', trans('messages.create_your_account'))

@section('content')
    
    <div class="row mt-5 mc-form">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            
            <h1 class="mb-20">{{ trans('messages.error') }}</h1>
            
            <div class="alert alert-warning" style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                <div style="display: flex; flex-direction: row; align-items: center;">
                    <div style="margin-right:15px">
                        <span class="material-symbols-rounded">
error_outline
</span>
                    </div>
                    <div style="padding-right: 40px">
                        <h4>{{ trans('messages.sender.status.failed.title') }}</h4>
                        <p>{{ trans('messages.sender.status.failed.description') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <form method="GET" action="{{ action('SenderController@index') }}">
                        <button type='submit' class="btn btn-secondary res-button">{{ trans('messages.go_back') }}</button>
                    </form>
                </div>
            </div>

            <hr>
            <div class="row flex align-items">
                <div class="col-md-12" style="font-size:13px;font-style: italic">
                    {{ trans('messages.sender.status.failed.note') }}
                </div>
                    
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
    
@endsection
