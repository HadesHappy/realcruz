@extends('layouts.popup.small')

@section('bar-title')
    {{ $template->name }}
@endsection

@section('content')
    <form class="categories-form" action="{{ action('TemplateController@categories', [
        'uid' => $template->uid,
    ]) }}"
        method="POST">

        <h2 class="mt-0 mb-2">{{ trans('messages.template.set_template_category') }}</h2>
        <p class="mt-0">{{ trans('messages.template.set_template_category.intro') }}</p>

        {{ csrf_field() }}

        <div class="categories-checkboxes">
            @foreach(Acelle\Model\TemplateCategory::all() as $category)
                @include('helpers.form_control', [
                    'type' => 'checkbox2',
                    'name' => 'categories['.$category->uid.']',
                    'value' => ($template->hasCategory($category) ? 'true' : 'false'),
                    'label' => $category->name,
                    'options' => ['false', 'true'],
                    'help_class' => 'template',
                    'rules' => [],
                ])
            @endforeach
        </div>

        <hr>

        <div class="mt-4">
            <button type="submit" class="btn btn-secondary">{{ trans('messages.save') }}</button>
        </div>
    </form>

    <script>
        $('.categories-form').on('submit', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();

            addMaskLoading();

            // 
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                success: function (response) {
                    removeMaskLoading();

                    // notify
                    notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
}); 

                    categoriesPopup.hide();

                    TemplatesIndex.getList().load();
                }
            });
        })
            
    </script>
@endsection