@extends('layouts.popup.small')

@section('title')
    {!! trans('messages.copy_plan', [
        'name' => $plan->name
    ]) !!}
@endsection

@section('content')
        <form id="copyPlanForm"
            action="{{ action('Admin\PlanController@copy', ['copy_plan_uid' => $plan->uid]) }}"
            method="POST">
            {{ csrf_field() }}
                
            <p class="mb-2">{{ trans('messages.what_would_you_like_to_name_your_plan') }}</p>

            @include('helpers.form_control', [
                'type' => 'text',
                'label' => '',
                'name' => 'name',
                'value' => request()->has('name') ? request()->name : trans("messages.copy_of_plan", ['name' => $plan->name]),
                'help_class' => 'plan',
                'rules' => ['name' => 'required']
            ])
            
            <div class="mt-4">
                <button type="submit" id="doCopyButton" class="btn btn-secondary px-3 me-2">{{ trans('messages.copy') }}</button>
                <button type="button" class="btn btn-link fw-600" data-bs-dismiss="modal">{{ trans('messages.cancel') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    var PlanCopy = {
        copy: function(url, data) {
            PlanList.getCopyPopup().mask();
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

                PlanList.getCopyPopup().hide();
                PlanIndex.getList().load();

            }).fail(function(jqXHR, textStatus, errorThrown){
                // for debugging
                PlanList.getCopyPopup().loadHtml(jqXHR.responseText);
            }).always(function() {
                PlanList.getCopyPopup().unmask();
                removeButtonMask($('#doCopyButton'));
            });
        }
    }

    $(document).ready(function() {
        $('#copyPlanForm').on('submit', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();

            PlanCopy.copy(url, data);
        });
    });
</script>

@endsection