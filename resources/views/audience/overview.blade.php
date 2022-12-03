@extends('layouts.core.frontend')

@section('title', trans('messages.dashboard'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/echarts/echarts.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/echarts/dark.js') }}"></script>
@endsection

@section('content')
    <h2 class="mt-4 pt-2">
        {{ trans('messages.audience.manage') }}
    </h2>
    <p>{!! trans('messages.audience.intro') !!}</p>

    <div class="row mt-4 mb-4">
        <div class="col-md-3">
            <div class="bg-secondary p-3 shadow rounded-3 text-white">
                <div class="text-center">
                    <h2 class="text-semibold mb-1 mt-0">{{ number_with_delimiter($subscriberCount) }}</h2>
                    <div class="text-muted2 text-white">
                        {!! trans('messages.list.mail_list_count.desc', [
                            'count' => number_with_delimiter($subscribedCount)
                        ]) !!}                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-secondary p-3 shadow rounded-3 text-white">
                <div class="text-center">
                    <h2 class="text-semibold mb-1 mt-0">{{ number_to_percentage($activeContactPercent) }}</h2>
                    <div class="text-muted2 text-white">
                        {{ trans('messages.list.active_contact.desc') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-secondary p-3 shadow rounded-3 text-white">
                <div class="text-center">
                    <h2 class="text-semibold mb-1 mt-0">{{ number_with_delimiter($formCount) }}</h2>
                    <div class="text-muted2 text-white">
                        @if (Auth::user()->customer->forms()->count())
                            <p class="mb-0">{{ trans('messages.list.form.desc') }}</p>
                        @else
                            <p class="mb-0">{{ trans('messages.forms_not_yet') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <a href="{{ action('BlacklistController@index') }}" class="bg-secondary p-3 shadow rounded-3 text-white d-block">
                <div class="text-center">
                    <h2 class="text-semibold mb-1 mt-0">
                        {{ number_with_delimiter($blacklistedCount) }}
                    </h2>
                    <div class="text-muted2 text-white">
                        <p class="mb-0">{!! trans('messages.list.blacklist.desc') !!}</p>                        
                    </div>
                </div>
            </a>
        </div>
    </div>

    <h3 class="mt-5 mb-20"><span class="material-symbols-rounded me-2">
        insights
        </span> {{ trans('messages.audience.audience_growth.title') }}</h3>
    <p class="mb-4">{{ trans('messages.audience.audience_growth.desc') }}</p>
    <div class="border shadow-sm rounded">
        <div class="p-3">
            <div id="AudienceGrowthChart"
                class=""
                style="width:100%; height:350px;"
            ></div>
        </div>
    </div>
    
    <script>
        var AudienceGrowthChart = {
            url: '{{ action('AudienceController@growthChart') }}',
            getChart: function() {
                return $('#AudienceGrowthChart');
            },
    
            showChart: function() {
                $.ajax({
                    method: "GET",
                    url: this.url,
                })
                .done(function( response ) {
                    AudienceGrowthChart.renderChart( response );
                });
            },
    
            renderChart: function(data) {
                    // based on prepared DOM, initialize echarts instance
                    var my2Chart = echarts.init(AudienceGrowthChart.getChart()[0], ECHARTS_THEME);
    
                    var option = {
                        tooltip: {
                            trigger: 'axis'
                        },
                        legend: {
                            data: ['{{ trans('messages.total') }}', '{{ trans('messages.unsubscribed') }}']
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        toolbox: {
                            show: false
                        },
                        xAxis: {
                            type: 'category',
                            boundaryGap: false,
                            data: data.columns
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [
                            {
                                name: '{{ trans('messages.total') }}',
                                type: 'line',
                                itemStyle: {
                                    color: '#5cb2b2'
                                },
                                data: data.total
                            },
                            {
                                name: '{{ trans('messages.unsubscribed') }}',
                                type: 'line',
                                itemStyle: {
                                    color: '#b26e59'
                                },
                                data: data.unsubscribed
                            }
                        ]
                    };
    
                    // use configuration item and data specified to show chart
                    my2Chart.setOption(option);
            }
        }
    
        AudienceGrowthChart.showChart();
    </script>

    <h3 class="mt-5 mb-20"><span class="material-symbols-rounded me-2">
        open_in_new
        </span> {{ trans('messages.audience.quick_action') }}</h3>
    <p class="mb-4">{{ trans('messages.audience.quick_action.desc') }}</p>

    <div class="my-4">
        <div class="quick-action-items">
            <div class="quick-action py-3 border-top">
                <div class="d-flex align-items-center">
                    <div class="me-4">
                        <img width="180px" src="{{ url('images/quick-action-sign-up-form.svg') }}" />
                    </div>
                    <div>
                        <label class="fs-6 fw-600 mb-1">{{ trans('messages.create_a_signup_form') }}</label>
                        <p class="mb-0 text-muted fst-italic">
                            {!! trans('messages.create_a_signup_form.desc') !!}
                        </p>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ action('FormController@index') }}" class="btn btn-secondary py-2 px-4">
                            {{ trans('messages.form.create') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="quick-action py-3 border-top">
                <div class="d-flex align-items-center">
                    <div class="me-4">
                        <img width="180px" src="{{ url('images/import-contacts.png') }}" />
                    </div>
                    <div>
                        <label class="fs-6 fw-600 mb-1">{{ trans('messages.audience.import_contacts.title') }}</label>
                        <p class="mb-0 text-muted fst-italic">
                            {!! trans('messages.create_a_signup_form.desc') !!}
                        </p>
                    </div>
                    <div class="ms-auto">
                        <a href="javascript:;" class="btn btn-primary py-2 px-4 import-select-list">
                            {{ trans('messages.audience.import_contacts.title') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var AudienceOverviewImportSelectList = {
            url: '{!! action('MailListController@selectList', [
                'redirect' => action('SubscriberController@import', [
                    'list_uid' => 'list_uid',                    
                ]),
                'title' => trans('messages.audience.import_contacts.title'),
            ]) !!}',

            popup: null,

            getPopup: function() {
                if (this.popup == null) {
                    this.popup = new Popup({
                        url: this.url
                    });
                }

                return this.popup;
            },

            load: function() {
                this.getPopup().load();
            }
        }

        $(function() {
            $('.import-select-list').on('click', function() {
                AudienceOverviewImportSelectList.load();
            });
        });
    </script>
@endsection
