@extends('layouts.core.backend')

@section('title', trans('messages.upload_template'))

@section('page_header')

			<div class="page-title">				
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li class="breadcrumb-item"><a href="{{ action("Admin\TemplateController@index") }}">{{ trans('messages.language') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><span class="material-symbols-rounded">
file_upload
</span> {{ trans('messages.upload_language') }}</span>
				</h1>				
			</div>

@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
        
            <div class="alert alert-info">
                {!! trans('messages.language_upload_guide') !!}
            </div>
        
            <form enctype="multipart/form-data" action="{{ action('Admin\LanguageController@upload', $language->uid) }}" method="POST" class="form-validate-jquery">
                {{ csrf_field() }}							
                
                @include('helpers.form_control', ['required' => true, 'type' => 'file', 'label' => trans('messages.upload_file'), 'name' => 'file'])
                    
                <div class="text-end">
                    <button class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.upload') }}</button>
                    <a href="{{ action('Admin\LanguageController@index') }}" class="btn btn-link"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
                </div>
                
            </form>  
            
        </div>
    </div>
@endsection
