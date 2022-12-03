@extends('layouts.popup.medium')

@section('content')

    <div class="tos-box">
        {!! Acelle\Model\Setting::get('terms_of_service.content') !!}
    </div>

    <hr>

    <div class="text-center">
        <button class="btn btn-secondary px-4 close">{{ trans('messages.tos.i_agree') }}</button>
    </div>
@endsection