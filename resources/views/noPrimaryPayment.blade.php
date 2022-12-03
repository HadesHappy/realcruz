@extends('layouts.core.login')

@section('title', trans('messages.not_authorized'))

@section('content')
    <div class="alert alert-danger alert-styled-left">
        <span class="text-semibold">
            {{ trans('messages.no_primary_payment') }}
        </span>
    </div>
    <a href='{{ action('Admin\PaymentController@index') }}' onclick='history.back();return false;' class='btn btn-secondary'>{{ trans('messages.go_to_admin_dashboard') }}</a>
@endsection