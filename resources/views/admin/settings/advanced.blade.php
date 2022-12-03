@extends('layouts.core.backend')

@section('title', trans('messages.settings'))

@section('page_header')

			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
				</ul>
				<h1>
					<span class="text-gear"><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.settings') }}</span>
				</h1>
			</div>

@endsection

@section('content')
    <form action="{{ action('Admin\SettingController@advanced') }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}

        <div class="tabbable">

            <div class="tab-content">

                @include("admin.settings._advanced")

            </div>
        </div>

    </form>
@endsection
