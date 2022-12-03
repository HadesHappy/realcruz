@extends('layouts.core.login')

@section('title', trans('messages.something_went_wrong'))

@section('content')
    <div class="alert alert-danger alert-styled-left">
        <span class="text-semibold">
            {!! $message !!}
        </span>
    </div>
    <a href='#back' onclick='history.back();return false;' class='btn btn-secondary'>{{ trans('messages.go_back') }}</a>
@endsection
