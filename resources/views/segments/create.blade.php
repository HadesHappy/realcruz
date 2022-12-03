@extends('layouts.core.frontend')

@section('title', $list->name . ": " . trans('messages.create_subscriber'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/datetime/anytime.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.date.js') }}"></script>
@endsection
	
@section('page_header')
			
			@include("lists._header")

@endsection

@section('content')
	
	@include("lists._menu")
	
	<h2 class="text-semibold text-primary my-4"><span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_segment') }}</h2>
	
	<form action="{{ action('SegmentController@store', $list->uid) }}" method="POST" class="form-validate-jqueryz">
		{{ csrf_field() }}
		
		<div class="row">
			<div class="col-md-12">
				@include("segments._form")
				<hr>
				<div class="text-left">
					<button class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
					<a href="{{ action('SegmentController@index', $list->uid) }}" class="btn btn-link"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
				</div>
			</div>
		</div>
	<form>

	<script>
		// add segment condition
		$(document).on("click", ".add-segment-condition", function(e) {
			// ajax update custom sort
			$.ajax({
				method: "GET",
				url: $(this).attr('sample-url'),
			})
			.done(function( msg ) {
				var num = "0";

				if($('.segment-conditions-container .condition-line').length) {
					num = parseInt($('.segment-conditions-container .condition-line').last().attr("rel"))+1;
				}

				msg = msg.replace(/__index__/g, num);

				$('.segment-conditions-container').append(msg);

				var new_line = $('.segment-conditions-container .condition-line').last();
				// new_line.find('select').select2({
				// 	templateResult: formatSelect2TextOption,
				// 	templateSelection: formatSelect2TextSelected
				// });
				// new_line.find('select').trigger('change');
				
				initJs(new_line);
				new_line.find('select').trigger('change');
			});
		});

		// add segment condition
		$(document).on("change", ".condition-line .operator-col select", function(e) {
			var op = $(this).val();

			if(op == 'blank' || op == 'not_blank') {
				$(this).parents(".condition-line").find('.value-col').css("visibility", "hidden");
			} else {
				$(this).parents(".condition-line").find('.value-col').css("visibility", "visible");
			}
		});

		// Segment condition field type select
		$(document).on('change', '.condition-field-select', function() {
			var line = $(this).parents('.condition-line');
			var field_uid = $(this).val();
			var value_col = line.find('.operator_value_col');
			var url = value_col.attr('data-url');
			var index = line.attr('rel');
			var operator = line.find('.operator-col select').val();

			value_col.html('');

			if (field_uid != '') {
				$.ajax({
					type: 'GET',
					url: url,
					data: {
						field_uid: field_uid,
						index: index,
						operator: field_uid
					}, // serializes the form's elements.
					success: function(data)
					{
						value_col.html(data);
						// value_col.find('select').select2();
						initJs(value_col);
						value_col.find('select').select2();
					}
				});
			}
		});
	</script>
@endsection
