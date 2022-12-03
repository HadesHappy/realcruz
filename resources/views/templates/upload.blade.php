@extends('layouts.core.frontend')

@section('title', trans('messages.upload_template'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("TemplateController@index") }}">{{ trans('messages.templates') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">
file_upload
</span> {{ trans('messages.upload_template') }}</span>
        </h1>
    </div>

@endsection

@section('content')

    <div class="row">
        <div class="col-md-8">
            <p>{!! trans('messages.template.upload.instruction', ["link" => url('/download/Sample-Template.zip') ]) !!}</p>

            <div class="alert alert-info">
                {{ trans('messages.template.upload.warning') }}
            </div>

            <form enctype="multipart/form-data" action="{{ action('TemplateController@uploadTemplate') }}" method="POST" class="ajax_upload_form form-validate-jquery">
                {{ csrf_field() }}

                <input type="hidden" name="type" value="{{ Acelle\Model\Template::TYPE_EMAIL }}" />

                @include('helpers.form_control', ['required' => true, 'type' => 'text', 'label' => trans('messages.template_name'), 'name' => 'name', 'value' => old('name'), 'rules' => ['name' => 'required']])

                @include('helpers.form_control', ['required' => true, 'type' => 'file', 'label' => trans('messages.upload_file'), 'name' => 'file'])

                <div class="text-end">
                    <button class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.upload') }}</button>
                    <a href="{{ action('TemplateController@index') }}" class="btn btn-link"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
                </div>

            </form>
        </div>
    </div>

@endsection
