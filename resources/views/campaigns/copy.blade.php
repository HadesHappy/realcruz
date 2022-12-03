@extends('layouts.popup.small')

@section('title')
    <span class="material-symbols-rounded me-1 text-muted2">content_copy</span> {!! trans('messages.copy_campaign', [
        'name' => $campaign->name
    ]) !!}
@endsection

@section('content')
        <form id="copyCampaginForm"
            action="{{ action('CampaignController@copy', ['copy_campaign_uid' => $campaign->uid]) }}"
            method="POST">
            {{ csrf_field() }}
                
            <p class="mb-2">{{ trans('messages.what_would_you_like_to_name_your_campaign') }}</p>

            @include('helpers.form_control', [
                'type' => 'text',
                'label' => '',
                'name' => 'name',
                'value' => request()->has('name') ? request()->name : trans("messages.copy_of_campaign", ['name' => $campaign->name]),
                'help_class' => 'campaign',
                'rules' => ['name' => 'required']
            ])
            
            <div class="mt-4 text-center">
                <button type="submit" id="doCopyButton" class="btn btn-secondary px-3 me-2">{{ trans('messages.copy') }}</button>
                <button type="button" class="btn btn-link fw-600" data-bs-dismiss="modal">{{ trans('messages.cancel') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    var CampaignsCopy = {
        copy: function(url, data) {
            CampaignsList.getCopyPopup().mask();
            addButtonMask($('#doCopyButton'));

            // copy
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                globalError: false
            }).done(function(response) {
                notify({
                    type: 'success',
                    message: response,
                });

                CampaignsList.getCopyPopup().hide();
                CampaignsIndex.getList().load();

            }).fail(function(jqXHR, textStatus, errorThrown){
                // for debugging
                CampaignsList.getCopyPopup().loadHtml(jqXHR.responseText);
            }).always(function() {
                CampaignsList.getCopyPopup().unmask();
                removeButtonMask($('#doCopyButton'));
            });
        }
    }

    $(document).ready(function() {
        $('#copyCampaginForm').on('submit', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();

            CampaignsCopy.copy(url, data);
        });
    });
</script>

@endsection