<!-- Basic modal -->
<div id="export-segments-modal" class="modal fade list-form-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-header">
            <button role="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">{{ trans('messages.export') }}</h4>
        </div>
        <div class="modal-content">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="">
            <input type="hidden" name="uids" value="">

            <div class="modal-body">
                <h4>{{trans("messages.field_export")}}</h4>
                <div class="fields-export">
                    @foreach ($list->getFields as $field)
                        @include('helpers.form_control', [
                                'type' => 'checkbox2',
                                'name' => $field->tag,
                                'value' => $field->tag,
                                'label' => trans($field->tag),
                                'options' => [$field->tag,$field->tag],
                            ])
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="exportCSV" data-dismiss="modal" class="btn btn-primary bg-teal">{{ trans('messages.export') }}</button>
                <button role="button" class="btn btn-default" data-dismiss="modal">{{ trans('messages.Cancel') }}</button>

            </div>
        </div>
    </div>
</div>
<!-- /basic modal -->
<script>
    $(function() {
        $("#exportCSV").click(function() {
            var t = [];
            $(".fields-export input").each(function() {
                if($(this).prop('checked')) {
                    t.push($(this).val());
                }
            })

            $.ajax({
                url: "{{ action('SegmentController@export', ["list_uid" => $list->uid, "uid" => $uid]) }}",
                data: {
                    _token: $("input[name='_token']").val(),
                    fields: t.join(",")

                },
                type: "POST",
                success: function() {
                }
            })
        })
    })
</script>