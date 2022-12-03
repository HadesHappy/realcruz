<h4 class="mb-3">{{ trans('messages.automation.operation.' . request()->operation) }}</h4>
<p>{{ trans('messages.automation.operation.' .request()->operation. '.desc') }}</p>

<input type="hidden" name="options[operation_type]" value="update" />

<div class="row my-2">
    <div class="col-md-5 font-weight-semibold">
        {{ trans('messages.operation.choose_field_to_update') }}
    </div>
    <div class="col-md-5 font-weight-semibold">
        {{ trans('messages.operation.field_value') }}
    </div>
</div>

<div class="update_list">

</div>

<div class="row my-2">
    <div class="col-md-10 font-weight-semibold">
        <div class="text-end">
            <a href="javascript:;" class="btn btn-primary btn-sm add-more-operation">
                {{ trans('messages.operation.add_more') }}
            </a>
        </div>
    </div>
</div>


<script>
    class FieldUpdate {
        constructor() {
            this.items = [
                @if (isset($element))
                    @foreach($element->getOption('update') as $key => $update)
                        {field_uid: '{{ $update->field_uid }}', value: '{{ $update->value }}'},
                    @endforeach
                @endif
            ];

            if (!this.items.length) {
                this.items.push({field_uid: '', value: ''});
            }
        }

        fetchItem(item, index) {
            var html = `
                <div class="row my-2">
                    <div class="col-md-5">
                        @include('helpers.form_control', [
                            'type' => 'select',
                            'class' => '',
                            'include_blank' => trans('messages.automation.choose_list_field'),
                            'name' => 'options[update][`+index+`][field_uid]',
                            'value' => '`+item.field_uid+`',
                            'help_class' => 'operation',
                            'options' => $automation->getListFieldOptions(),
                            'rules' => ['options.update.`+index+`.field_uid' => 'required'],
                        ])
                    </div>
                    <div class="col-md-5 font-weight-semibold">
                        @include('helpers.form_control', [
                            'type' => 'text',
                            'class' => '',
                            'name' => 'options[update][`+index+`][value]',
                            'value' => '`+item.value+`',
                            'help_class' => 'operation',
                            'rules' => ['options.update.`+index+`.value' => 'required'],
                        ])
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:;" class="btn btn-link text-danger remove-item" data-index="`+index+`">{{ trans('messages.remove') }}</a>
                    </div>
                </div>
            `
            html = html.replace('value="'+item.field_uid+'"', 'value="'+item.field_uid+'" selected');
            return html;
        }

        addItem() {
            this.items.push({field_uid: '', value: ''});
        }

        update() {
            var index = 0;
            var _this = this;
            _this.items.forEach(function(item) {
                _this.items[index].field_uid = $('[name="options[update]['+index+'][field_uid]"]').val();
                _this.items[index].value = $('[name="options[update]['+index+'][value]"]').val();
                index += 1;
            });
        }

        render() {
            var _this = this;
            $('.update_list').html('');

            var index = 0;
            this.items.forEach(function(item) {
                $('.update_list').append(_this.fetchItem(item, index));
                index = index+1;
            });

            initJs($('.update_list'));

            $('.remove-item').on('click', function(e) {
                _this.remove($(this).attr('data-index'));
            });

            $('.update_list input, .update_list select').on('change', function(e) {
                _this.update();
            });
        }

        remove(index) {
            this.items.splice(index, 1);
            this.render();
        }
    }

    var listItems = new FieldUpdate();
    listItems.render();

    customValidate($('#operation-edit'));

    $('.add-more-operation').on('click', function(e) {
        e.preventDefault();

        listItems.addItem();
        listItems.render();
    });

    
</script>
