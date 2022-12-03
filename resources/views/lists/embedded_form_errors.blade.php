@extends('layouts.core.page')

@section('title', trans('messages.error'))
	


@section('content')
  <div class="row">
    <div class="col-md-12 tex-center">
        <div style="margin: 100px auto; width: 400px;">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <a href="javascript:;" onclick="window.history.back()" class="btn btn-primary">{{ trans('messages.return_back') }}</a>
        </div>
    </div>
  </div>

@endsection