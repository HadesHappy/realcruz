@extends('layouts.core.frontend')

@section('title', $list->name . ": " . trans('messages.export'))

@section('page_header')

    @include("lists._header")

@endsection

@section('content')

    @include("lists._menu")

    <h2 class="text-primary my-4"><span class="material-symbols-rounded">
people
</span> {{ trans('messages.export_subscribers') }}</h2>

    <div class="row">
        <div class="col-md-8">
            <form action="#" id="formExport" method="POST" class="ajax_upload_form form-validate-jquery">
                {{ csrf_field() }}

                <div class="upload_file before">
                    <p>{!! trans('messages.click_to_start_export', ['total' => $list->readCache('SubscriberCount', 0)]) !!}</p>
                    <div class="form-group control-radio">
                        <div class="radio_box mb-4" data-popup='tooltip' title="">
                            <label class="main-control">
                                <input type="radio"
                                    {{ request()->segment_uid ? '' : 'checked' }}
                                    name="which"
                                    value="whole_list" class="styled" /> <rtitle>{{ trans('messages.export.whole_list') }} ({{ $list->readCache('SubscriberCount', 0) }} {{ strtolower(trans('messages.subscribers')) }})</rtitle>
                            </label>
                        </div>
                        <div class="radio_box {{ $list->segments()->count() ? '' : 'disabled' }}" data-popup='tooltip' title="">
                            <label class="main-control">
                                <input type="radio"
                                    {{ request()->segment_uid ? 'checked' : '' }}
                                    
                                    name="which"
                                    value="segment" class="styled" /> <rtitle>{{ trans('messages.export.choose_segment') }}</rtitle>
                            </label>
                            <div class="radio_more_box pt-3">
                                @include('helpers.form_control', [
                                    'value' => '',
                                    'type' => 'select',
                                    'name' => 'segment_uid',
                                    'label' => '',
                                    'value' => request()->segment_uid,
                                    'options' => $list->getSegmentSelectOptions(),
                                    'placeholder' => trans('messages.export.choose_segment'),
                                ])
                            </div>
                        </div>                        
                    </div>
                    <div class="text-left">
                        <button class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.export') }}</button>
                    </div>
                    <br />
                </div>

                <div class="form-group processing hide">
                    <h4 style="margin-bottom: 20px" id="notice">{!! trans('messages.please_wait_export') !!}</h4>
                    <div class="progress progress-lg">

                        <!--<div class="progress-bar progress-success bg-success-400" style="width: 20%">
                            <span class="sr-only"><span class="number">20</span>% Complete</span>
                        </div>-->

                        <div class="progress-bar progress-error progress-bar-danger" style="width: 0%">
                            <span><span class="number">0</span>% {{ trans('messages.error') }}</span>
                        </div>

                        <div class="progress-bar progress-total active" style="width: 0%">
                            <span><span class="number">0</span>% {{ trans('messages.complete') }}</span>
                        </div>

                    </div>
                    <label style="margin-bottom:20px;font-style:italic;" id="bottomNotice"></label>
                    <a id="cancelBtn" class="btn btn-secondary btn-icon cancel processing">
                        {{ trans('messages.cancel') }}
                    </a>
                </div>

                <div class="form-group finish hide">
                    <div class="text-left">
                        <a id="downloadBtn" target="_blank" href="#" role="button" class="btn btn-secondary success">
                            <span class="material-symbols-rounded">
file_download
</span> {{ trans('messages.download_export') }}
                        </a>
                        <a href="#retry" class="btn btn-secondary me-1 retry"><span class="material-symbols-rounded">
restart_alt
</span> {{ trans('messages.retry') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        var AcelleExport = {
            // Current export job if any
            currentExportJobUid: null,

            progressCheckUrl: null,

            cancelUrl: null,

            downloadUrl: null,

            progressCheck: null,

            resetCurrentJob: function() {
                AcelleExport.currentExportJobUid = null;
                AcelleExport.progressCheckUrl = null;
                AcelleExport.cancelUrl = null;
                AcelleExport.downloadUrl = null;
            },

            setCurrentJob: function(data) {
                AcelleExport.currentExportJobUid = data.currentExportJobUid;
                AcelleExport.progressCheckUrl = data.progressCheckUrl;
                AcelleExport.cancelUrl = data.cancelUrl;
                AcelleExport.downloadUrl = data.downloadUrl;
            },

            // Update import progress
            updateProgressBar: function(percentage, message) { // percentage from 0 to 100
                var form = $("form.ajax_upload_form");
                var bar = form.find('.progress-total');

                form.find("#bottomNotice").show();
                form.find("#bottomNotice").html(message);
                bar.find(".number").html(percentage);
                bar.css({
                    width: (percentage) + '%'
                });
            },

            stopCheckingProgress: function() {
                clearTimeout(AcelleExport.progressCheck);
            },

            checkProgress: function(completeAlert = true) {
                var form = $("form.ajax_upload_form");
                var bar = form.find('.progress-total');
                var bar_s = form.find('.progress-success');
                var bar_e = form.find('.progress-error');

                $.ajax({
                    url : AcelleExport.progressCheckUrl,
                    type: "GET",
                    success: function(result, textStatus, jqXHR) {
                        // Upgrade progress, no matter which status is
                        AcelleExport.showProgressBar();
                        AcelleExport.updateProgressBar(result.percentage, result.message);

                        if (result.status == "failed") {
                            AcelleExport.showFinishButtonBar();
                            AcelleExport.hideCancelButton();
                            $("#notice").hide();
                            $("#bottomNotice").hide();
                            $('#errorBox').show();
                            $('#errorMsg').html(result.error);
                        } else if (result.status == "done") {
                            AcelleExport.hideCancelButton();
                            $("#notice").show();
                            $("#notice").html('{!! trans('messages.export_completed') !!}');
                            $('#bottomNotice').show();
                            $("#bottomNotice").html(result.message);
                            form.find('.upload_file .progress-bar').addClass('success');
                            form.find('.finish').removeClass('hide');
                            form.find('.success').removeClass("hide");
                            

                            if (completeAlert) {
                                // Success alert
                                notify({
                                    title: "{{ trans('messages.notify.success') }}",
                                    message: '{{ trans('messages.export_completed') }}',
                                });
                            }
                        } else if (result.status == "cancelled") {
                            /*
                            AcelleExport.hideProgressBar();
                            form.find('.finish').addClass("hide");
                            form.find('.success').removeClass("hide");
                            */
                        } else if (result.status == "running" || result.status == "queued") {
                            AcelleExport.showProgressBar();
                            AcelleExport.progressCheck = setTimeout(function() {
                                 AcelleExport.checkProgress();
                            }, 2000);
                        }
                    }
                });
            },

            export: function() {
                var which = $('[name="which"]:checked').val();
                var data = {
                    _token: $('form#formExport').find('input[name="_token"]').val()
                };

                if (which == 'segment') {
                    data.segment_uid = $('[name=segment_uid]').val();
                }

                AcelleExport.showProgressBar();
                AcelleExport.updateProgressBar(0, "{{ trans('messages.starting') }}");

                //$(".processing label").html("{{ trans('messages.starting') }}");
                //$(".before").addClass('hide');
                //$(".processing").removeClass('hide');
                $.ajax({
                    url: '{{ action('SubscriberController@dispatchExportJob', [ 'list_uid' => $list->uid]) }}',
                    type: 'POST',
                    data: data,
                    success: function (data) {
                        AcelleExport.setCurrentJob(data);
                        AcelleExport.checkProgress();
                    }
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                    alert("Ajax failed");
                    return true;
                    AcelleExport.hideProgressBar();
                    notify({
                        title: "{{ trans('messages.notify.error') }}",
                        message: errorThrown,
                    });
                });
            },

            cancel: function() {
                AcelleExport.stopCheckingProgress();

                var token = $('form#formExport').find('input[name="_token"]').val();

                $.ajax({
                    url : AcelleExport.cancelUrl,
                    type: "POST",
                    data: {
                        '_token': token
                    },
                    success: function(result, textStatus, jqXHR) {
                        AcelleExport.hideFinishButtonBar();
                        AcelleExport.hideProgressBar();
                        AcelleExport.resetCurrentJob();
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    notify({
                        title: "{{ trans('messages.notify.error') }}",
                        message: errorThrown,
                    });

                    // Resume progress checking
                    AcelleExport.checkProgress();

                    return false;
                });
            },

            // Toggle: show progress bar, hide input upload bar
            showProgressBar: function() {
                // Also hide upload input
                $(".before").addClass('hide');
                $(".processing").removeClass('hide');
                //$('#errorBox').hide();
            },

            hideProgressBar: function() {
                // Also show upload input
                $(".before").removeClass('hide');
                $(".processing").addClass('hide');
                //$('#errorBox').hide();
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
        }

        $(document).ready(function() {

            $(document).on("submit", "form.ajax_upload_form", function(e) {
                e.preventDefault();

                AcelleExport.export();
            });

            $(document).on("click", "#cancelBtn", function(e) {
                e.preventDefault();

                var cancelConfirm = confirm("{{ trans('messages.list.export.cancel') }}");

                if (cancelConfirm) {
                    AcelleExport.cancel();
                }
            });

            $(document).on("click", ".retry", function(e) {
                e.preventDefault();

                AcelleExport.cancel();
            });

            $(document).on("click", "#downloadBtn", function(e) {
                e.preventDefault();

                window.location.href = AcelleExport.downloadUrl;
                return false;
            });


            // SET CURRENT JOB IF ANY
            @if (isset($currentJobUid))
                // Temporary show the progress bar of 0 percentage, waiting for the checkProgress() call to update it
                AcelleExport.showProgressBar();
                AcelleExport.updateProgressBar(0, 'Initializing...');

                // Set up current job information
                AcelleExport.setCurrentJob({
                    currentExportJobUid: '{{ $currentJobUid }}',
                    progressCheckUrl: '{{ $progressCheckUrl }}',
                    cancelUrl: '{{ $cancelUrl }}',
                    downloadUrl: '{{ $downloadUrl }}'
                });

                // false means do not show the alert popup when progress is complete
                // Don't worry, this is for the first check only
                AcelleExport.checkProgress(false);
            @endif
        });
    </script>

@endsection