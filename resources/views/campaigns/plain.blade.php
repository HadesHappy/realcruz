@extends('layouts.core.frontend')

@section('title', trans('messages.campaigns') . " - " . trans('messages.template'))
	
@section('head')      
    <script type="text/javascript" src="{{ URL::asset('core/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/js/editor.js') }}"></script>
@endsection

@section('page_header')
	
	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
			<li class="breadcrumb-item"><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
		</ul>
		<h1>
			<span class="text-semibold"><span class="material-symbols-rounded me-2">
forward_to_inbox
</span> {{ $campaign->name }}</span>
		</h1>

		@include('campaigns._steps', ['current' => 3])
	</div>

@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h2 class="mt-0">{{ trans('messages.campaign.content_management') }}</h2>
            <h3>{{ trans('messages.campaign.email_content_plain') }}</h3>                
            <p>{{ trans('messages.campaign.email_content_plain.intro') }}</p>
                
            <form action="{{ action('CampaignController@plain', $campaign->uid) }}" method="POST">
                {{ csrf_field() }}

                @include('helpers.form_control', [
                    'type' => 'textarea',
                    'class' => 'campaign-plain-text',
                    'name' => 'plain',
                    'value' => $campaign->plain,
                    'label' => '',
                    'help_class' => 'campaign',
                    'rules' => ['plain' => 'required'],
                ])

                <button class="btn btn-secondary">{{ trans('messages.campaign.plain.save') }}</button>
            </form>
        </div>
    </div>
@endsection
