@extends('layouts.core.page')

@section('title', trans('messages.login'))

@section('content')

<div class="preview_page_cover"></div>

{!! $page->content !!}

@endsection