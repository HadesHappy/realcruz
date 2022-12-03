@extends('layouts.core.frontend')

@section('title', trans('messages.campaigns') . " - " . trans('messages.schedule'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/datetime/anytime.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.date.js') }}"></script>
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

        @include('campaigns._steps', ['current' => 4])
    </div>

@endsection

@section('content')
    <form id="CampaignScheduleForm" action="{{ action('CampaignController@schedule', $campaign->uid) }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}
        
        <input type="hidden" name="send_now" value="no" />
        <div class="row">
            <div class="col-md-3 list_select_box" target-box="segments-select-box" segments-url="{{ action('SegmentController@selectBox') }}">
                @include('helpers.form_control', ['type' => 'date',
                    'class' => '_from_now',
                    'name' => 'delivery_date',
                    'label' => trans('messages.delivery_date'),
                    'value' => $delivery_date,
                    'rules' => $rules,
                    'help_class' => 'campaign'
                ])
            </div>
            <div class="col-md-3 segments-select-box">
                @include('helpers.form_control', ['type' => 'time',
                    'name' => 'delivery_time',
                    'label' => trans('messages.delivery_time'),
                    'value' => $delivery_time,
                    'rules' => $rules,
                    'help_class' => 'campaign'
                ])
            </div>
        </div>
        
        <hr>
        <div class="text-end">
            <button class="btn btn-secondary me-1">
                <span class="material-symbols-rounded me-1">
                    alarm
                    </span>
                    {{ trans('messages.campaign.schedule') }} 
            </button>
            <button type="button" class="btn btn-primary send-now">
                <span class="material-symbols-rounded me-1">
                    done_all
                </span>
                {{ trans('messages.campaign.send_now') }}
            </button>
        </div>
    <form>

    <script>
        var CampaignSchedule = {
            getForm: function() {
                return $('#CampaignScheduleForm');
            },

            schedule: function() {
                this.getForm().find('[name=send_now]').val('no');
                this.getForm().submit();
            },

            sendNow: function() {
                this.getForm().find('[name=send_now]').val('yes');
                this.getForm().submit();
            }
        }
        
        $(function() {
            $('.send-now').on('click', function() {
                CampaignSchedule.sendNow();
            });
        });
    </script>
@endsection
