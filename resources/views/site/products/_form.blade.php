<div class="row">
    <div class="col-md-4">
        @include('helpers.form_control', [
            'type' => 'text',
            'class' => '',
            'label' => trans('messages.product.title'),
            'name' => 'title',
            'value' => $product->title,
            'help_class' => 'product',
            'rules' => ['title' => 'required'],
        ])

        @include('helpers.form_control', [
            'type' => 'text',
            'class' => '',
            'label' => trans('messages.product.sku'),
            'name' => 'sku',
            'value' => $product->sku,
            'help_class' => 'product',
            'rules' => ['title' => 'required'],
        ])

        @include('helpers.form_control', [
            'type' => 'textarea',
            'class' => '',
            'name' => 'description',
            'value' => $product->description,
            'help_class' => 'product',
        ])

        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => trans('messages.product.categories'),
            'name' => 'categories[]',
            'value' => [],
            'help_class' => 'trigger',
            'options' => $categories,
            'multiple' => 'true',
            'placeholder' => trans('messages.product.select_categories'),
        ])

        @include('helpers.form_control', [
            'type' => 'number',
            'class' => '',
            'name' => 'price',
            'value' => $product->price,
            'help_class' => 'product',
        ])
    </div>
    <div class="col-md-8">
        @include('helpers.form_control', [
            'type' => 'textarea',
            'class' => 'clean-editor',
            'name' => 'content',
            'value' => $product->content,
            'help_class' => 'product',
        ])
    </div>
</div>
<hr>
<div class="mt-4">
    <button class="btn btn-secondary me-1"><span class="material-symbols-rounded">
        done
        </span> {{ trans('messages.save') }}</button>
    <a target="_blank" href="{{ config('wordpress.url') . '/wp-admin/post-new.php?post_type=product' }}" role="button" class="btn btn-light">
        <i class="icon-cross2"></i> {{ trans('messages.product.advanced') }}
    </a>
    <a href="{{ action('Site\ProductController@index') }}" role="button" class="btn btn-light">
        <i class="icon-cross2"></i> {{ trans('messages.cancel') }}
    </a>
</div>
