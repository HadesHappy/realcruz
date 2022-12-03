@include('automation2._back')

<h4 class="mb-3">{{ trans('messages.automation.operation.' . $element->getOption('operation_type')) }}</h4>
<p>{!! trans('messages.automation.operation.' .$element->getOption('operation_type'). '.desc', [
    'list_name' => $element->getOption('target_list_uid') ? \Acelle\Model\MailList::findByUid($element->getOption('target_list_uid'))->name : '',
    'tags' => $element->getOption('tags') ? '<span class="label bg-running mr-1">' . implode('</span><span class="label bg-running mr-1">', $element->getOption('tags')) . '</span>' : '',
]) !!}</p>

@if (request()->operation == 'update' && $element->getOption('update'))
    <table class="table my-3" style="border-bottom: solid 1px #ddd">
        <thead class="thead-light">
            <tr>
                <th width="40%" scope="col">{{ trans('messages.field') }}</th>
                <th scope="col">{{ trans('messages.value') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($element->getOption('update') as $update)
                <tr>
                    <th scope="row">{{ \Acelle\Model\Field::findByUid($update->field_uid)->label }}</th>
                    <td>{{ $update->value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@php
    $operation = request()->operation;
    if (in_array(request()->operation, ['copy','move','delete'])) {
        $operation = 'copy';
    }
@endphp

<a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@operationEdit', [
    'uid' => $automation->uid,
    'operation' =>  $operation . '_contact',
    'id' => request()->id,
]) }}')" class="btn btn-secondary me-1">{{ trans('messages.automation.edit_operation') }}</a>

<a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@operationSelect', [
    'uid' => $automation->uid,
    'id' => request()->id,
]) }}')" class="btn btn-light">{{ trans('messages.automation.operation.change') }}</a>

<div class="mt-4 d-flex py-3">
    <div>
        <h4 class="mb-2">
            {{ trans('messages.automation.dangerous_zone') }}
        </h4>
        <p class="">
            {{ trans('messages.automation.action.delete.wording') }}                
        </p>
        <div class="mt-3">
            <a href="javascript:;" data-confirm="{{ trans('messages.automation.action.delete.confirm') }}"
                class="btn btn-secondary operation-delete">
                <span class="material-symbols-rounded">
delete
</span> {{ trans('messages.automation.remove_this_action') }}
            </a>
        </div>
    </div>
</div>

<script>

    $('.operation-delete').on('click', function(e) {
        e.preventDefault();
        
        var confirm = $(this).attr('data-confirm');
        var dialog = new Dialog('confirm', {
            message: confirm,
            ok: function(dialog) {
                // remove current node
                tree.getSelected().remove();
                
                // save tree
                saveData(function() {
                    sidebar.load();
                    // notify
                    notify('success', '{{ trans('messages.notify.success') }}', '{{ trans('messages.automation.action.deteled') }}');
                    
                    // load default sidebar
                    sidebar.load('{{ action('Automation2Controller@settings', $automation->uid) }}');
                });
            },
        });
    });
</script>
