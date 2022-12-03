@extends('layouts.core.frontend')

@section('title', trans('messages.blacklist.import'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("BlacklistController@index") }}">{{ trans('messages.blacklist') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">
file_upload
</span> {{ trans('messages.blacklist.import') }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <form id="importForm" action="{{ action('BlacklistController@startImport') }}" method="POST" class="form-validate-jquery" enctype="multipart/form-data">
                {{ csrf_field() }}

                <h2 class="text-semibold mt-0">{{ trans('messages.blacklist.upload_list_from_file') }}</h2>

                <p>{!! trans('messages.blacklist.import.user_intro') !!}</p>
                <div class="alert alert-info">
                    {!! trans('messages.blacklist.import_file_help', [ 'max' => \Acelle\Library\Tool::maxFileUploadInBytes()]) !!}
                </div>

                @include('helpers.form_control', [
                    'required' => true,
                    'type' => 'file',
                    'label' => '',
                    'name' => 'file',
                    'value' => ''
                ])

                <div class="text-left">
                    <button class="btn btn-secondary me-2 click-effect"><i class="icon-check"></i> {{ trans('messages.import') }}</button>
                </div>

            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div id="progressBar" class="form-group processing hide">
                <h2 class="text-semibold mt-0">{{ trans('messages.blacklist.upload_list_from_file') }}</h2>

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
                <h4 style="margin-bottom: 20px" id="notice">{!! trans('messages.list.verify.progress') !!}</h4>
                <div class="progress progress-lg">

                    <div class="progress-bar progress-error progress-bar-danger" style="width: 0%">
                        <span><span class="number">0</span>% {{ trans('messages.error') }}</span>
                    </div>

                    <div id="percentageBar" class="progress-bar progress-total active" style="width: 0%">
                        <span><span class="number">0</span>% {{ trans('messages.complete') }}</span>
                    </div>

                </div>
                <label style="margin-bottom:20px;font-style:italic;" id="bottomNotice"></label>
                <a id="cancelBtn" class="btn btn-secondary btn-icon cancel processing">
                    {{ trans('messages.cancel') }}
                </a>

                <div class="form-group finish hide">
                    <div class="text-left">
                        <a id="doneBtn" target="_blank" href="#" type="button" class="btn btn-secondary success">
                            {{ trans('messages.label.done') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var AcelleImport = {
            
            // Current import job if any
            currentJobUid: null,
            progressCheckUrl: null,
            cancelUrl: null,

            progressCheck: null,

            // Update import progress
            updateProgressBar: function(percentage, message) { // percentage from 0 to 100
                $("#bottomNotice").show();
                $("#bottomNotice").html(message);
                $("#percentageBar span.number ").html(percentage);
                $("#percentageBar").css({
                    width: (percentage) + '%'
                });
            },

            resetCurrentJob: function() {
                this.currentJobUid = null;
                this.progressCheckUrl = null;
                this.cancelUrl = null;
            },

            setCurrentJob: function(data) {
                this.currentJobUid = data.currentJobUid;
                this.progressCheckUrl = data.progressCheckUrl;
                this.cancelUrl = data.cancelUrl;
            },

            // Toggle: show progress bar, hide input upload bar
            hideProgressBar: function() {
                $("#progressBar").addClass('hide');
                $("#importForm").removeClass('hide');
            },

            showProgressBar: function() {
                $("#progressBar").removeClass('hide');
                $("#importForm").addClass('hide');
            },

            showCancelButton: function() {
                $('#cancelBtn').removeClass('hide');
            },

            hideCancelButton: function() {
                $('#cancelBtn').addClass('hide');
            },

            checkProgress: function(completeAlert = true) {
                $.ajax({
                    url : AcelleImport.progressCheckUrl,
                    type: "GET",
                    success: function(result, textStatus, jqXHR) {
                        // Upgrade progress, no matter which status is
                        AcelleImport.showProgressBar();
                        AcelleImport.updateProgressBar(result.percentage, result.message);

                        if (result.status == "failed") {
                            $("#notice").hide();
                            $("#bottomNotice").hide();
                            $('#errorBox').show();
                            $('#errorMsg').html(result.error);
                        } else if (result.status == "done") {
                            AcelleImport.showFinishButtonBar();
                            AcelleImport.hideCancelButton();
                            $("#notice").show();
                            $("#notice").html('{!! trans('messages.blacklist.import_process_done') !!}');
                            $('#bottomNotice').show();
                            $("#bottomNotice").html(result.message);

                            if (completeAlert) {
                                // Success alert
                                notify({
                                    title: "{{ trans('messages.notify.success') }}",
                                    message: '{{ trans('messages.blacklist.import_process_done') }}',
                                });
                            }
                        } else if (result.status == "cancelled") {
                            /*
                            AcelleImport.hideProgressBar();
                            form.find('.finish').addClass("hide");
                            form.find('.success').removeClass("hide");
                            */
                        } else if (result.status == "running" || result.status == "queued") {
                            AcelleImport.showProgressBar();
                            AcelleImport.progressCheck = setTimeout(function() {
                                 AcelleImport.checkProgress();
                            }, 2000);
                        } else {
                            alert('Invalid result status');
                            console.log(result);
                        }
                    }
                });
            },

            showFinishButtonBar: function() {
                $(".finish").removeClass('hide');
            },

            hideFinishButtonBar: function() {
                $(".finish").addClass('hide');
            },

            stopCheckingProgress: function() {
                clearTimeout(AcelleImport.progressCheck);
            },

            upload: function() {
                var form = $("form.ajax_upload_form");

                if (!form.valid()) {
                    $("label.error").insertAfter(".uploader");
                    return false;
                }

                var formData = new FormData(form[0]); // Make the upload form and submit
                var url = form.attr('action');
                AcelleImport.showProgressBar();
                AcelleImport.updateProgressBar(0, "{{ trans('messages.uploading') }}");

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        // Set the JobID to query progress
                        // Upon receiving the response containing the job_id as well as progress_check_url
                        // Make another request to query progress
                        AcelleImport.setCurrentJob(data);
                        AcelleImport.checkProgress();
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                    AcelleImport.hideProgressBar();
                    notify({
                        title: "{{ trans('messages.notify.error') }}",
                        message: errorThrown,
                    });
                });
            },

            importAnotherFile: function() {
                // Same as cancel, simply delete the only import job associated with list
                AcelleImport.stopCheckingProgress();
                var token = $('form#importForm').find('input[name="_token"]').val();

                $.ajax({
                    url : AcelleImport.cancelUrl,
                    type: "POST",
                    data: {
                        '_token': token
                    },
                    success: function(result, textStatus, jqXHR) {
                        AcelleImport.hideFinishButtonBar();
                        AcelleImport.hideProgressBar();
                        AcelleImport.resetCurrentJob();
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    notify({
                        title: "{{ trans('messages.notify.error') }}",
                        message: errorThrown,
                    });

                    // Resume progress checking
                    AcelleImport.checkProgress();
                });
            },

            cancel: function() {
                AcelleImport.stopCheckingProgress();
                var token = $('form#importForm').find('input[name="_token"]').val();

                $.ajax({
                    url : AcelleImport.cancelUrl,
                    type: "POST",
                    data: {
                        '_token': token
                    },
                    success: function(result, textStatus, jqXHR) {
                        AcelleImport.hideFinishButtonBar();
                        AcelleImport.hideProgressBar();
                        AcelleImport.resetCurrentJob();
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    notify({
                        title: "{{ trans('messages.notify.error') }}",
                        message: errorThrown,
                    });

                    // Resume progress checking
                    AcelleImport.checkProgress();

                    return false;
                });
            }
        }

        $(document).ready(function() {
            // Events binding
            $(document).on("click", "#doneBtn", function(e) {
                e.preventDefault();

                // DONE = Cancel = simply delete job
                AcelleImport.cancel();
            });

            $(document).on("click", "#cancelBtn", function(e) {
                e.preventDefault();

                var cancelConfirm = confirm("{{ trans('messages.blacklist.cancel.confirm') }}");

                if (cancelConfirm) {
                    AcelleImport.cancel();
                }
            });

            // Set up current job, trigger progressCheck
            @if (isset($currentJobUid))
                // Temporary show the progress bar of 0 percentage, waiting for the checkProgress() call to update it
                AcelleImport.showProgressBar();
                AcelleImport.updateProgressBar(0, 'Initializing...');

                // Set up current job information
                AcelleImport.setCurrentJob({
                    currentJobUid: '{{ $currentJobUid }}',
                    progressCheckUrl: '{{ $progressCheckUrl }}',
                    cancelUrl: '{{ $cancelUrl }}',
                });

                // false means do not show the alert popup when progress is complete
                // Don't worry, this is for the first check only
                AcelleImport.checkProgress(false);
            @endif
        });

    </script>

@endsection
