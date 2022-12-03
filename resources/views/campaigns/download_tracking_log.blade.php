@extends('layouts.core.empty')

@section('title', $campaign->name)

@section('page_header')

    @include("campaigns._header")

@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col" style="padding-top:20px">
                <p align="center" id='inprogress'>{{ trans('messages.campaign.log.download.inprogress') }}<span id="progress">0%</span></p>
                <p align="center" id='done' style="display:none">{{ trans('messages.campaign.log.download.complete') }}<br><a id="download" href="#">{{ trans('messages.tracking_log.download') }}</a></p>
            </div>
        </div>
    </div>

    <script>
        var interval;
        var checkProgress = function() {
            $.ajax({
                url: "{{ action('CampaignController@trackingLogExportProgress', [ 'uid' => $job->uid] ) }}"
            }).done(function( data, textStatus, jqXHR ) {
                if (data.status == 'done') {
                    $("#progress").html(data.progress * 100 + "%");
                    clearTimeout(interval);
                    $("#done").show();
                    $("#inprogress").hide();
                    $("#download").click(function(){
                        window.opener.downloadAndCloseDownloadWindow(data.download);
                    });
                } else {
                    interval = setTimeout(checkProgress, 2500);
                }
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                alert(errorThrown);
            });
        };

        $(document).ready(function() {
            checkProgress();
        });
    </script>
@endsection