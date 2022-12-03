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
        <div class="row">
			<div class="col-sm-12 col-md-6 col-lg-6">
				<div class="sub-section">
					<h2 class="text-semibold text-primary my-4"><span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_subscriber') }}</h2>

					<form action="{{ action('SubscriberController@store', $list->uid) }}" method="POST" class="form-validate-jquery">
						{{ csrf_field() }}

						@include("subscribers._form")

                        @if (\Acelle\Model\Setting::get('import_subscribers_commitment'))
                            <hr>
                            <div class="mt-5">
                                @include('helpers.form_control', [
                                    'type' => 'checkbox2',
                                    'class' => 'policy_commitment mb-10 required',
                                    'name' => 'policy_commitment',
                                    'value' => 'no',
                                    'required' => true,
                                    'label' => \Acelle\Model\Setting::get('import_subscribers_commitment'),
                                    'options' => ['no','yes'],
                                    'rules' => []
                                ])
                            </div>
                        @endif

						<div class="text-left">
							<button class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
							<a href="{{ action('SubscriberController@index', $list->uid) }}" class="btn btn-link"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
						</div>
					<form>
				</div>
			</div>
		</div>
        <script>
            $(function() {
                // policy_commitment
                $('.policy_commitment').each(function () {
                    var input = $(this);
                    var form = input.closest('form');


                    form.submit(function(e) {
                        if (form.valid()) {
                            if (input.is(':checked')) {
                                return true;
                            } else {
                                e.preventDedault();
                                return false;
                            }
                        }
                    });

                });
            });
        </script>
@endsection
