@extends('layouts.core.login')

@section('title', trans('messages.app.notice.title'))

@section('content')
    <div class="alert bg-info alert-styled-left">
        <span class="text-semibold">
            {!! $message !!}
        </span>
    </div>
    <a href='#back' onclick='history.back();return false;' class='btn btn-secondary'>{{ trans('messages.go_back') }}</a>
@endsection
