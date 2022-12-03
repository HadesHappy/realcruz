@extends('layouts.core.backend')

@section('title', $language->name)

@section('page_header')
    
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="material-symbols-rounded">translate</i> {{ $language->name }}</span>
        </h1>
    </div>
                
@endsection

@section('content')
    
    <div class="row">
        <div class="col-md-6">
            {!! trans('messages.language.translate.intro') !!}

            <form action="{{ action('Admin\LanguageController@translate', [
                'id' => $language->uid,
            ]) }}" method="GET">

                <div class="d-flex align-items-center">
                    <div class="form-group-mb-0 pe-2" style="width:100%">
                        @include('helpers.form_control', [
                            'type' => 'select',
                            'name' => 'file_id',
                            'value' => '',
                            'label' => '',
                            'options' => $language->getLanguageFileOptions(),
                        ])
                    </div>
                    <div>
                        <button type="submit" class="btn btn-secondary text-nowrap">{{ trans('messages.language.load_translate') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
@endsection