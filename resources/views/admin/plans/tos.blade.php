@extends('layouts.core.backend')

@section('title', $plan->name)

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/js/editor.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Admin\PlanController@index") }}">{{ trans('messages.plans') }}</a></li>
        </ul>
        <h1 class="mc-h1">
            <span class="text-semibold">{{ $plan->name }}</span>
        </h1>
    </div>

@endsection

@section('content')
    
    @include('admin.plans._menu')
    
    <form enctype="multipart/form-data" action="{{ action('Admin\PlanController@tos', $plan->uid) }}" method="POST" class="form-validate-jqueryx">
        {{ csrf_field() }}
        
        <div class="row">
            <div class="col-md-7">
                <div class="mc_section">
                    <h2>{{ trans('messages.plan.tos') }}</h2>
                        
                    <p>{{ trans('messages.plan.tos.intro') }}</p>
                        
                    <div class="form-group-checkboxes">                        
                        @include('helpers.form_control', [
                            'class' => '',
                            'type' => 'checkbox2',
                            'name' => 'terms_of_service[enabled]',
                            'label' => trans('messages.plan.disable_terms_of_service'),
                            'value' => Acelle\Model\Setting::get('terms_of_service.enabled', 'yes'),
                            'options' => ['no','yes'],
                            'help_class' => 'plan',
                            'rules' => $plan->validationRules()['options'],
                        ])
                    </div>
                    
                    @include('helpers.form_control', [
                        'class' => 'builder-editor',
                        'type' => 'textarea',
                        'name' => 'terms_of_service[content]',
                        'label' => trans('messages.plan.tos'),
                        'value' => Acelle\Model\Setting::get('terms_of_service.content'),
                        'help_class' => 'plan',
                    ])
                    
                    <button class="btn btn-secondary me-2">{{ trans('messages.save') }}</button>
                    <a href="{{ action('Admin\PlanController@index') }}" role="button" class="btn btn-link">
                        {{ trans('messages.cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </form>
    
    <script>
        $(function() {
            var manager = new GroupManager();

            manager.add({
                checkbox: $('[type=checkbox][name="terms_of_service[enabled]"]')
            });

            manager.bind(function(group) {
                var check = function() {
                    if ( group.checkbox.is(':checked') ) {
                        tinymce.activeEditor.setMode('readonly');
                    } else {
                        tinymce.activeEditor.setMode('design');
                    }
                };

                group.checkbox.on('change', function() {
                    check();
                });

                check();
            });
        });
    </script>
@endsection
