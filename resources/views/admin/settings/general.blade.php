@extends('layouts.core.backend')

@section('title', trans('messages.settings'))

@section('head')
	<script type="text/javascript" src="{{ URL::asset('core/tinymce/tinymce.min.js') }}"></script>        
    <script type="text/javascript" src="{{ URL::asset('core/js/editor.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-gear"><span class="material-symbols-rounded">
                tune
</span> {{ trans('messages.settings') }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <form action="{{ action('Admin\SettingController@general') }}" method="POST" class="form-validate-jqueryz" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="tabbable">
            @include("admin.settings._tabs")
            <div class="tab-content">
                @include("admin.settings._general")                        
            </div>
        </div>
    </form>

    <script>
        function changeSelectColor() {
            $('.select2 .select2-selection__rendered, .select2-results__option').each(function() {
                var text = $(this).html();
                if (text == '{{ trans('messages.default') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='icon-square text-teal-600'></i>");
                    }
                }
                if (text == '{{ trans('messages.blue') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='icon-square text-blue'></i>");
                    }
                }
                if (text == '{{ trans('messages.green') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='icon-square text-green'></i>");
                    }
                }
                if (text == '{{ trans('messages.brown') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='icon-square text-brown'></i>");
                    }
                }
                if (text == '{{ trans('messages.pink') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='icon-square text-pink'></i>");
                    }
                }
                if (text == '{{ trans('messages.grey') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='icon-square text-grey'></i>");
                    }
                }
                if (text == '{{ trans('messages.white') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='icon-square text-white'></i>");
                    }
                }
            });
        }

        $(document).ready(function() {
            setInterval("changeSelectColor()", 100);
        });
    </script>
@endsection
