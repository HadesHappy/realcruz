@extends('layouts.core.backend')

@section('title', trans('messages.license'))

@section('page_header')

    <div class="page-title">
        <h1>Quick Upgrade
        </h1>
    </div>

@endsection

@section('content')

    <form action="{{ action('Admin\SettingController@upgradeFromUrl') }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}

        <p>Patch URL</p>
		<input type="text" name="url" size="80">
		<input type="submit" name="Upgrade">

    </form>
@endsection
