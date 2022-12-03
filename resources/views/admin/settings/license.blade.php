@extends('layouts.core.backend')

@section('title', trans('messages.license'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-gear"><span class="material-symbols-rounded">
vpn_key
</span> {{ trans('messages.license') }}</span>
        </h1>
    </div>

@endsection

@section('content')

    <form action="{{ action('Admin\SettingController@license') }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}

        <div class="tabbable">
            @include("admin.settings._tabs")

            <div class="tab-content">

				@if ($license_error)
					<div class="alert alert-danger">
						{{ $license_error }}
					</div>
				@endif


                @foreach ($settings as $name => $setting)
                    @if (array_key_exists('cat', $setting) && $setting['cat'] == 'license')
                        @if ($current_license)
							<div class="sub-section">
								<h3>{{ trans('messages.license.your_license') }}</h3>
								<p>{{ trans('messages.your_current_license') }} <strong>{{ trans('messages.license_label_' . \Acelle\Model\Setting::get('license_type')) }}</strong></p>
								<div class="d-flex align-items-center">
									<h4 class="mb-0">
										{{ $current_license }}
									</h4>
									<a href="{{ action('Admin\SettingController@licenseRemove') }}" class="btn btn-default ms-4" link-confirm="{{ trans('messages.license.remove.confirm') }}" link-method="POST">
										<i class="material-symbols-rounded">delete</i>
										{{ trans('messages.license.remove') }}
									</a>
								</div>
									
							</div>
                        @else
							<div class="sub-section">
								<h3>{{ trans('messages.license.your_license') }}</h3>
								<p> {{ trans('messages.license.no_license') }} </p>
							</div>
						@endif

						<div class="sub-section">
							<h3>{{ trans('messages.license.license_types') }}</h3>
							{!! trans('messages.license_guide') !!}
						</div>

						<div class="sub-section">
							@if (!$current_license)
								<h3>{{ trans('messages.verify_license') }}</h3>
							@else
								<h3>{{ trans('messages.change_license') }}</h3>
							@endif
							<div class="row license-line">
								<div class="col-md-6">
									@include('helpers.form_control', [
										'type' => $setting['type'],
										'class' => (isset($setting['class']) ? $setting['class'] : "" ),
										'name' => $name,
										'value' => (request()->license ? request()->license : ''),
										'label' => trans('messages.enter_license_and_click_verify'),
										'help_class' => 'setting',
										'options' => (isset($setting['options']) ? $setting['options'] : "" ),
										'rules' => Acelle\Model\Setting::rules(),
									])
								</div>
								<div class="col-md-6">
									<br />
									<div class="text-left">
										@if ($current_license)
											<button class="btn btn-secondary"><i class="icon-check"></i> {{ trans('messages.change_license') }}</button>
										@else
											<button class="btn btn-secondary"><i class="icon-check"></i> {{ trans('messages.verify_license') }}</button>
										@endif
									</div>
								</div>
							</div>
						</div>
                    @endif
                @endforeach
            </div>
        </div>


    </form>
@endsection
