@extends('layouts.core.page')

@section('title', trans('messages.select_a_plan'))

@section('content')
    <div class="row">
        <div class="col-sm-2 col-md-3">
            
        </div>
        <div class="col-sm-8 col-md-6">
            <h2 class="text-semibold mt-5 text-white">{{ trans('messages.activation_email_sent_title') }}</h2>
            <div class="panel panel-body p-4 rounded-3 bg-white shadow">                        
                {!! trans('messages.activation_email_resent_content') !!}
            </div>
        </div>
    </div>
@endsection