@extends('layouts.core.login')

@section('title', trans('messages.offline'))

@section('content')
    <div class="alert bg-grey-600 alert-styled-left">
        <span class="text-semibold">
            {{ Acelle\Model\Setting::get("site_offline_message") }}
        </span>
    </div>
@endsection