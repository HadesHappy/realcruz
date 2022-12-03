@extends('layouts.core.page')

@section('title', $page->subject)

@section('head')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('core/prismjs/prism.css') }}">
    <script type="text/javascript" src="{{ URL::asset('core/prismjs/prism.js') }}"></script>


    <script type="text/javascript" src="{{ URL::asset('core/datetime/anytime.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.date.js') }}"></script>

@endsection

@section('content')

	<form action="" method="POST" class="form-validate-jqueryz">
		{{ csrf_field() }}
		
		{!! $page->content !!}
	
	</form>
		
@endsection