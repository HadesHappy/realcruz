@extends('layouts.core.frontend')

@section('title', $list->name)

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/echarts/echarts.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/echarts/dark.js') }}"></script> 
@endsection

@section('page_header')

    @include("lists._header")

@endsection

@section('content')

    @include("lists._menu")

    <div class="row">
        <div class="col-md-8">
            <h2 class="text-semibold my-4" style="margin-bottom: 10px">{{ trans('messages.list_verification') }}</h2>

            <div id="progressBar" class="form-group processing hide">
                <div id="errorBox" class="alert alert-danger" style="display: none; flex-direction: row; align-items: center; justify-content: space-between;">
                        <div style="display: flex; flex-direction: row; align-items: center;">
                            <div style="margin-right:15px">
                                <i class="lnr lnr-circle-minus"></i>
                            </div>
                            <div style="padding-right: 40px">
                                <h4>ERROR</h4>
                                <p id="errorMsg"></p>
                            </div>
                        </div>
                    </div>
                <p style="margin-bottom: 20px" id="notice">{!! trans('messages.list.verify.progress') !!}</p>
                <div class="progress progress-lg">

                    <div class="progress-bar progress-error progress-bar-danger" style="width: 0%">
                        <span><span class="number">0</span>% {{ trans('messages.error') }}</span>
                    </div>

                    <div class="progress-bar progress-total active" style="width: 0%">
                        <span><span class="number">0</span>% {{ trans('messages.complete') }}</span>
                    </div>

                </div>
                <label style="margin-bottom:20px;font-style:italic;" id="bottomNotice"></label>
                <a role="button" id="cancelBtn" class="btn btn-secondary btn-icon cancel processing">
                    {{ trans('messages.cancel') }}
                </a>

                <div class="form-group finish hide">
                    <div class="text-left">
                        <a id="doneBtn" target="_blank" href="#" type="button" class="btn btn-secondary success">
                            {{ trans('messages.label.done') }}
                        </a>
                        <a id="retryBtn" href="retryBtn" class="btn bg-grey-600 me-2 retry"><span class="material-symbols-rounded">
restart_alt
</span> {{ trans('messages.retry') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="verifyForm">
        <div class="col-md-6">
            <form action="#" method="POST" class="form-validate-jquery">
                {{ csrf_field() }}
                <p>{{ trans('messages.list.verify.intro') }}</p>
                @include('helpers.form_control', [
                    'type' => 'select',
                    'name' => 'email_verification_server_id',
                    'value' => '',
                    'options' => \Auth::user()->customer->emailVerificationServerSelectOptions(),
                    'help_class' => 'verification',
                    'rules' => ['email_verification_server_id' => 'required'],
                    'include_blank' => trans('messages.select_email_verification_server')
                ])

                <div class="text-left">
                    <button id="startBtn" class="btn btn-secondary me-2"> {{ trans('messages.verification.button.start') }}</button>
                </div>
            </form>
            
            <!-- if -->
            <h2 class="text-semibold" style="margin-top:40px;margin-bottom:10px">{{ trans('messages.verification_status') }}</h2>
            <p>{!! trans('messages.verification_process_not_running', [
                'verified' => $list->subscribers()->verified()->count(),
                'total' => number_with_delimiter($list->readCache('SubscriberCount')),
            ]) !!}</p>
            <p>
                <a link-confirm="{{ trans('messages.reset_list_verification_confirm') }}" link-method="POST" class="btn btn-secondary"
                    href="{{ action("MailListController@resetVerification", $list->uid) }}">
                        {{ trans('messages.verification.button.reset') }}
                </a>
            </p>

        </div>

        <div class="col-md-6">

            <div class="row">
                <div class="col-md-12">
                    @if (true)
                        <!-- Basic column chart -->
                        <div class="" style="margin-top:-50px">
                            <div class="">
                                <div class="chart-container px-5">
                                    <div class="chart has-fixed-height-250"
                                        id="emailVerificationChart"
                                        style="width: 100%;height:450px;"
                                        data-url="{{ action('Admin\PlanController@pieChart') }}"
                                    ></div>
                                </div>
                            </div>
                        </div>
                        <!-- /basic column chart -->
                    @endif
                </div>
            </div>
            
            <script>
                $(function() {
                    EmailVerificationChart.showChart();
                });
                var EmailVerificationChart = {
                    url: '{{ action('MailListController@emailVerificationChart', [
                        'uid' => $list->uid,
                    ]) }}',
                    getChart: function() {
                        return $('#emailVerificationChart');
                    },
            
                    showChart: function() {
                        $.ajax({
                            method: "GET",
                            url: this.url,
                        })
                        .done(function( response ) {
                            EmailVerificationChart.renderChart( response.data );
                        });
                    },
            
                    renderChart: function(data) {
                        // based on prepared DOM, initialize echarts instance
                        var chart = echarts.init(EmailVerificationChart.getChart()[0], ECHARTS_THEME);
            
                        var colors = [
                            '#5cb2b2',
                            '#b25977',
                            '#aab25a',
                            '#5b7bb2',
                            '#555555',
                            '#626eb2',
                            '#81ac8d',
                            '#7d5fb2',
                            '#b26e59'
                        ];
            
                        var cData = data.map(function(item, index) {
                            return {
                                name: item.name,
                                value: item.value,
                                itemStyle: {
                                    color: colors[index],
                                    borderWidth: 1,  borderType: 'solid', borderColor: '#fff'
                                }
                            };
                        });
            
                        var option = {
                            title: {
                                text: '{{ trans('messages.email_verification.statistics') }}',
                                // subtext: '{{ trans('messages.statistics_chart') }}',
                                left: 'center'
                            },
                            tooltip: {
                                trigger: 'item',
                                formatter: '{b}: {c} ({d}%)'
                            },
                            legend: {
                                left: 'center',
                                bottom: 0,
                            },
                            series: [
                                {
                                    selectedMode: 'single',
                                    type: 'pie',
                                    radius: '60%',
                                    center: ['50%', '47%'],
                                    data: cData,
                                    emphasis: {
                                        itemStyle: {
                                            shadowBlur: 10,
                                            shadowOffsetX: 0,
                                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                                        }
                                    },
                                    label: {
                                        // position: 'inner',
                                        fontSize: 12,
                                        color: ECHARTS_THEME == 'dark' ? '#fff' : null,
                                        formatter: '{b}\n{d}% ({c})',
                                    },
                                }
                            ]
                        };
            
                        // use configuration item and data specified to show chart
                        chart.setOption(option);
                    }
                }    
            </script>
            
        </div>
    </div>

    <script>
        var AcelleList = {
            
            // Current job
            currentJobUid: null,
            progressCheckUrl: null,
            cancelUrl: null,

            progressCheck: null,

            setCurrentJob: function(data) {
                this.currentJobUid = data.currentJobUid;
                this.progressCheckUrl = data.progressCheckUrl;
                this.cancelUrl = data.cancelUrl;
            },

            resetCurrentJob: function() {
                AcelleList.currentJobUid = null;
                AcelleList.progressCheckUrl = null;
                AcelleList.cancelUrl = null;
            },

            start: function() {
                var serverUID = $('select[name="email_verification_server_id"]').find(':selected').val();
                var token = $('#verifyForm').find('input[name="_token"]').val();

                if (!serverUID) {
                    alert('Please choose a verification server from the list');
                    return;
                }

                $.ajax({
                    url: '{{ action('MailListController@startVerification', [ 'uid' => $list->uid ] ) }}',
                    type: 'POST',
                    data: {
                        '_token': token,
                        'email_verification_server_id': serverUID
                    },
                    success: function (data) {
                        $("#notice").show();
                        $("#bottomNotice").hide();
                        $('#errorBox').hide();
                        AcelleList.hideFinishButtonBar();
                        AcelleList.setCurrentJob(data);
                        AcelleList.checkProgress();
                    }
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                    alert("Cannot start verification process: " + errorThrown);
                });
            },

            updateProgressBar: function(percentage, message) { // percentage from 0 to 100
                var form = $("#progressBar");
                var bar = form.find('.progress-total');

                form.find("#bottomNotice").show();
                form.find("#bottomNotice").html(message);
                bar.find(".number").html(percentage);
                bar.css({
                    width: (percentage) + '%'
                });
            },

            stopCheckingProgress: function() {
                clearTimeout(AcelleList.progressCheck);
            },

            checkProgress: function(completeAlert = true) {
                $.ajax({
                    url : AcelleList.progressCheckUrl,
                    type: "GET",
                    success: function(result, textStatus, jqXHR) {
                        // Upgrade progress, no matter which status is
                        AcelleList.showProgressBar();
                        AcelleList.updateProgressBar(result.percentage, result.message);

                        if (result.status == "failed") {
                            AcelleList.showFinishButtonBar();
                            AcelleList.hideCancelButton();
                            $("#notice").hide();
                            $("#bottomNotice").hide();
                            $('#errorBox').show();
                            $('#errorMsg').html(result.error);
                        } else if (result.status == "done") {
                            AcelleList.showFinishButtonBar();
                            AcelleList.hideCancelButton();
                            $("#notice").show();
                            $("#notice").html('{{ trans('messages.list.verify.complete') }}');
                            $('#bottomNotice').show();
                            $("#bottomNotice").html(result.message);

                            if (completeAlert) {
                                // Success alert
                                new Dialog('alert', {
                                    title: "{{ trans('messages.notify.success') }}",
                                    message: '{{ trans('messages.list.verify.complete') }}'
                                });
                            }
                        } else if (result.status == "cancelled") {
                            /*
                            AcelleList.hideProgressBar();
                            form.find('.finish').addClass("hide");
                            form.find('.success').removeClass("hide");
                            */
                        } else if (result.status == "running" || result.status == "queued") {
                            AcelleList.showProgressBar();
                            AcelleList.progressCheck = setTimeout(function() {
                                 AcelleList.checkProgress();
                            }, 2000);
                        }
                    }
                });
            },

            cancel: function() {
                AcelleList.stopCheckingProgress();

                var token = $('#verifyForm').find('input[name="_token"]').val();

                $.ajax({
                    url : AcelleList.cancelUrl,
                    type: "POST",
                    data: {
                        '_token': token
                    },
                    success: function(result, textStatus, jqXHR) {
                        // AcelleList.hideProgressBar();
                        // AcelleList.resetCurrentJob();

                        // Reload the page to refresh the verification stats
                        window.location.href = '{{ action("MailListController@verification", [ "uid" => $list->uid ]) }}';
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);

                    // Resume progress checking
                    AcelleList.checkProgress();

                    return false;
                });
            },

            // Toggle: show progress bar, hide input upload bar
            hideProgressBar: function() {
                $("#progressBar").addClass('hide');
                $("#verifyForm").removeClass('hide');
            },

            showProgressBar: function() {
                $("#progressBar").removeClass('hide');
                $("#verifyForm").addClass('hide');
            },

            showFinishButtonBar: function() {
                $(".finish").removeClass('hide');
            },

            hideFinishButtonBar: function() {
                $(".finish").addClass('hide');
            },

            showCancelButton: function() {
                $('#cancelBtn').removeClass('hide');
            },

            hideCancelButton: function() {
                $('#cancelBtn').addClass('hide');
            },
        };

        $(document).ready(function() {
            // EVENT BINDING
            $(document).on("click", "#startBtn", function(e) {
                e.preventDefault();

                AcelleList.start();
            });

            $(document).on("click", "#cancelBtn", function(e) {
                e.preventDefault();

                var cancelConfirm = confirm("{{ trans('messages.list.verify.cancel_confirm') }}");

                if (cancelConfirm) {
                    AcelleList.cancel();
                }
            });

            $(document).on("click", "#doneBtn", function(e) {
                e.preventDefault();

                // Done = Cancel
                AcelleList.cancel();
            });

            $(document).on("click", "#retryBtn", function(e) {
                e.preventDefault();

                // Done = Cancel
                AcelleList.cancel();
            });

            // SET UP CURRENT RUNNING JOB
            @if (isset($currentJobUid))
                // Temporary show the progress bar of 0 percentage, waiting for the checkProgress() call to update it
                AcelleList.showProgressBar();
                AcelleList.updateProgressBar(0, 'Initializing...');

                // Set up current job information
                AcelleList.setCurrentJob({
                    currentJobUid: '{{ $currentJobUid }}',
                    progressCheckUrl: '{{ $progressCheckUrl }}',
                    cancelUrl: '{{ $cancelUrl }}',
                });

                // false means do not show the alert popup when progress is complete
                // Don't worry, this is for the first check only
                AcelleList.checkProgress(false);
            @endif
        });

    </script>
@endsection
