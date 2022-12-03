@extends('layouts.core.login')

@section('title', trans('messages.not_authorized'))

@section('content')
    <div class="alert alert-danger alert-styled-left">
        <span class="text-semibold">
            {{ trans('messages.no_more_item') }}
        </span>
    </div>
    <a href='#back' onclick='history.back();return false;' class='btn btn-secondary'>{{ trans('messages.go_back') }}</a>
@endsection