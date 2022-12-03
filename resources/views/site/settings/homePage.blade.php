@extends('layouts.core.frontend')

@section('title', trans('messages.brand.shop_info'))

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
            <li class="breadcrumb-item">{{ trans('messages.brand.home_page') }}</li>
        </ul>
    </div>

@endsection

@section('content')
    @php
        $wordpress = new \Acelle\Library\WordpressManager();
        $pageID = get_option('page_on_front');
    @endphp
    <iframe id="wpFrame" src="{{ config('wordpress.url') }}/wp-admin/post.php?post={{ $pageID }}&action=edit"></iframe>
@endsection
