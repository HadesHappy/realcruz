@extends('layouts.core.frontend')

@section('title', trans('messages.campaigns') . " - " . trans('messages.template'))
    
@section('head')      
    <script type="text/javascript" src="{{ URL::asset('core/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/js/editor.js') }}"></script>

    <!-- Dropzone -->    
	<script type="text/javascript" src="{{ URL::asset('core/dropzone/dropzone.js') }}"></script>
    @include('helpers._dropzone_lang')
	<link href="{{ URL::asset('core/dropzone/dropzone.css') }}" rel="stylesheet" type="text/css">
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
        <div class="col-md-8 mb-0">
            <h2 class="mt-0 mb-3">{{ trans('messages.campaign.content_management') }}</h2>
            <div class="sub-section d-flex">
                <div class=" mr-auto pr-2">                    
                    <p>{{ trans('messages.campaign.email_content.intro') }}</p>
                        
                    <div class="media-left">
                        <div class="main">
                            <label>{{ trans('messages.campaign.html_email') }}</label>
                            <p>{{ trans('messages.campaign.html_email.last_edit', [
                                'date' => Auth::user()->customer->formatDateTime($campaign->updated_at, 'date_full'),
                            ]) }}</p>

                            <p class="mt-20">
                                @if (in_array(Acelle\Model\Setting::get('builder'), ['both','pro']) && $campaign->template->builder)
                                    <a href="{{ action('CampaignController@templateEdit', $campaign->uid) }}" class="btn btn-primary me-1 template-compose">
                                        {{ trans('messages.campaign.email_builder_pro') }}
                                    </a>
                                @endif
                                @if (in_array(Acelle\Model\Setting::get('builder'), ['both','classic']))
                                    <a href="{{ action('CampaignController@builderClassic', $campaign->uid) }}" class="btn btn-default template-compose-classic">
                                        {{ trans('messages.campaign.email_builder_classic') }}
                                    </a>
                                @endif
                                <a href="{{ action('CampaignController@templateCreate', $campaign->uid) }}" class="btn btn-link bg-grey-600 me-1">
                                    {{ trans('messages.campaign.change_template') }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="template-thumb-container ml-4">
                        <img class="automation-template-thumb" src="{{ $campaign->getThumbUrl() }}?v={{ Carbon\Carbon::now() }}" />
                        <a
                            onclick="popupwindow('{{ action('CampaignController@preview', $campaign->uid) }}', `{{ $campaign->name }}`, 800)"
                            href="javascript:;"
                            class="btn btn-primary" style="display:none"
                        >
                            {{ trans('messages.automation.template.preview') }}
                        </a>                           
                    </div>
                </div>
            </div>

            @if ($spamscore)
                <div class="sub-section">
                    <h2 class="mt-0 mb-3">{{ trans('messages.campaign.spam_score') }}</h2>
                    <p>{!! trans('messages.campaign.score.intro') !!}</p>
                    <a href="#" id="calculate-score" class="btn btn-primary bg-grey-600 me-1">
                        {{ trans('messages.campaign.check_spam_score') }}
                    </a>
                </div>
            @endif

            <div class="sub-section">   
                <h2 class="mt-0 mb-3">{{ trans('messages.campaign.attachment') }}</h2>
                <p>{{ trans('messages.campaign.attachment.intro') }}</p>
                    
                @include('campaigns._attachment')
            </div>
            
            
        </div>
    </div>
        
    <hr>
    <a href="{{ action('CampaignController@schedule', ['uid' => $campaign->uid]) }}" class="btn btn-secondary">
        {{ trans('messages.next') }} <span class="material-symbols-rounded">
arrow_forward
</span>
    </a>
        
    <script>
        var templatePopup = new Popup();        
    
        $(document).ready(function() {
            $('.template-start').click(function() {
                var url = $(this).attr('data-url');
                
                templatePopup.load(url);
            });

            $('.template-compose').click(function(e) {
                e.preventDefault();
                
                var url = $(this).attr('href');

                openBuilder(url);
            });
            
            $('.template-compose-classic').click(function(e) {
                e.preventDefault();
                
                var url = $(this).attr('href');

                openBuilderClassic(url);
            });
        });

        $('#calculate-score').click(function() {
            spamPopup = new Popup("{{ action('CampaignController@spamScore', ['uid' => $campaign->uid]) }}");
            spamPopup.load();
            return false;
        });
    </script>

@endsection
