@extends('layouts.core.frontend')

@section('title', trans('messages.create_admin'))

@section('head')
	<script type="text/javascript" src="{{ URL::asset('core/tinymce/tinymce.min.js') }}"></script>        
    <script type="text/javascript" src="{{ URL::asset('core/js/editor.js') }}"></script>

    <script src="{{ URL::asset('core/js/UrlAutoFill.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('core/css/vbrand.css') }}">
@endsection
	
@section('page_header')
	
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Site\MenuController@index") }}">{{ trans('messages.site.menus') }}</a></li>
        </ul>
    </div>

@endsection

@section('content')
    <iframe id="wpFrame" src="{{ config('wordpress.url') }}/wp-admin/nav-menus.php?action=edit&menu=0"></iframe>
@endsection
