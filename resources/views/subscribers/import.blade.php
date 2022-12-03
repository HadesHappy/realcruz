@extends('layouts.core.frontend')

@section('title', $list->name . ": " . trans('messages.import'))

@section('page_header')

    @include("lists._header")

@endsection

@section('content')

    @include("lists._menu")

    <h2 class="text-primary my-4"><span class="material-symbols-rounded">
people
</span> {{ trans('messages.import_subscribers') }}</h2>

    <div class="row">
        <div class="col-md-8">
            <form id="importForm"
                action="{{ action('SubscriberController@dispatchImportJob', ['list_uid' => $list->uid]) }}" method="POST" class="ajax_upload_form form-validate-jquery">
                {{ csrf_field() }}

                <div class="alert alert-info">
                    {!! trans('messages.list.import.instruction', [
                        'csv_link' => url('files/csv_import_example.csv'),
                        'size' => \Acelle\Library\Tool::maxFileUploadInBytes()
                    ]) !!}
                </div>
                @foreach ($importNotifications as $notification)
                <div class="alert alert-info">
                    {!! $notification !!}
                </div>
                @endforeach
                <div class="upload_file before">

                    @include('helpers.form_control', ['required' => true, 'type' => 'file', 'label' => trans('messages.upload_file'), 'name' => 'file', 'value' => $list->name])

                    @if (\Acelle\Model\Setting::get('import_subscribers_commitment'))
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
                        <hr>
                    @endif

                    <div class="text-left">
                        <button class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.import') }}</button>
                    </div>
                    <br />
                </div>

                <div class="form-group processing hide">
                    <h4 style="margin-bottom: 20px" id="notice">{!! trans('messages.please_wait_import') !!}</h4>
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
                    	<a id="downloadLog" target="_blank" href="#" role="button" class="btn btn-secondary">
                                <span class="material-symbols-rounded">
file_download
</span> {{ trans('messages.download_import_log') }}
                        </a>
                        <a href="#retry" class="btn btn-link me-2 retry"><span class="material-symbols-rounded">
restart_alt
</span> {{ trans('messages.import_another') }}</a>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script>
        var AcelleImport = {
            
            // Current import job if any
            currentImportJobUid: null,
            progressCheckUrl: null,
            cancelUrl: null,
            logDownloadUrl: null,

            progressCheck: null,

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

            resetCurrentJob: function() {
            	AcelleImport.currentImportJobUid = null;
                AcelleImport.progressCheckUrl = null;
                AcelleImport.cancelUrl = null;
                AcelleImport.logDownloadUrl = null;
            },

            setCurrentJob: function(data) {
            	AcelleImport.currentImportJobUid = data.currentImportJobUid;
                AcelleImport.progressCheckUrl = data.progressCheckUrl;
                AcelleImport.cancelUrl = data.cancelUrl;
                AcelleImport.logDownloadUrl = data.logDownloadUrl;
            },

            // Toggle: show progress bar, hide input upload bar
            showProgressBar: function() {
            	// Also hide upload input
                var form = $("form.ajax_upload_form");
                form.find('.before').addClass("hide");
                form.find(".processing").removeClass('hide');
                $('#errorBox').hide();
            },

            hideProgressBar: function() {
            	// Also show upload input
                var form = $("form.ajax_upload_form");
                form.find('.before').removeClass("hide");
                form.find(".processing").addClass('hide');
                $('#errorBox').hide();
            },

            showCancelButton: function() {
                var form = $("form.ajax_upload_form");
                form.find('.cancel').removeClass('hide');
            },

            hideCancelButton: function() {
                var form = $("form.ajax_upload_form");
                form.find('.cancel').addClass('hide');
            },

            checkProgress: function(completeAlert = true) {
            	var form = $("form.ajax_upload_form");
	            var bar = form.find('.progress-total');
	            var bar_s = form.find('.progress-success');
	            var bar_e = form.find('.progress-error');

	            $.ajax({
	                url : AcelleImport.progressCheckUrl,
	                type: "GET",
	                success: function(result, textStatus, jqXHR) {
	                    // Upgrade progress, no matter which status is
	                    AcelleImport.showProgressBar();
	                    AcelleImport.updateProgressBar(result.percentage, result.message);

	                    if (result.status == "failed") {
	                        AcelleImport.showFinishButtonBar();
	                        AcelleImport.hideCancelButton();
	                        $("#notice").hide();
	                        $("#bottomNotice").hide();
	                        $('#errorBox').show();
	                        $('#errorMsg').html(result.error);
	                    } else if (result.status == "done") {
	                        AcelleImport.hideCancelButton();
	                        $("#notice").show();
	                        $("#notice").html('{!! trans('messages.import_completed') !!}');
	                        $('#bottomNotice').show();
	                        $("#bottomNotice").html(result.message);
	                        form.find('.upload_file .progress-bar').addClass('success');
	                        form.find('.finish').removeClass('hide');
	                        form.find('.success').removeClass("hide");
	                        

	                        if (completeAlert) {
	                            // Success alert
                                new Dialog('alert', {
                                    title: "{{ trans('messages.notify.success') }}",
                                    message: '{!! trans('messages.import_completed') !!}',
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
                    new Dialog('alert', {
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
                    new Dialog('alert', {
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
                    new Dialog('alert', {
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
            
            // Event bindings
            $(document).on("submit", "form.ajax_upload_form", function() {
                AcelleImport.upload();
                return false; // avoid triggering the click action of <A>
            });

            $(document).on("click", ".retry", function() {
                AcelleImport.importAnotherFile();
                return false;
            });

            $(document).on("click", "#cancelBtn", function() {
                var cancelConfirm = confirm("{{ trans('messages.list.import.cancel') }}");

                if (cancelConfirm) {
                    AcelleImport.cancel();
                }

                return false;
            });

            $(document).on("click", "#downloadLog", function() {
                window.location.href = AcelleImport.logDownloadUrl;
                return false;
            });

            // In case of existing job, start checking it
            @if (isset($currentJobUid))
            	// Temporary show the progress bar of 0 percentage, waiting for the checkProgress() call to update it
                AcelleImport.showProgressBar();
                AcelleImport.updateProgressBar(0, 'Checking...');

                // Set up current job information
                AcelleImport.setCurrentJob({
					currentImportJobUid: '{{ $currentJobUid }}',
	                progressCheckUrl: '{{ $progressCheckUrl }}',
	                cancelUrl: '{{ $cancelUrl }}',
	                logDownloadUrl: '{{ $logDownloadUrl }}'
                });

                // false means do not show the alert popup when progress is complete
                // Don't worry, this is for the first check only
                AcelleImport.checkProgress(false);
            @endif
        });
    </script>
@endsection
