@extends('layouts.core.backend')

@section('title', $plan->name)

@section('head')
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
    
    <form enctype="multipart/form-data" action="{{ action('Admin\PlanController@save', $plan->uid) }}" method="POST" class="form-validate-jqueryx">
        <div class="row">
            <div class="col-md-8">
                {{ csrf_field() }}
                <div class="mc_section">
                    <h2>{{ trans('messages.plan.resources') }}</h2>
                    
                    <p>{{ trans('messages.plan.resource.intro') }}</p>

                    <div class="unlimited_controls">
                        <div class="mb-3 unlimited_control">
                            <div class="d-flex align-items-center">
                                <div class="me-4" style="width:400px">
                                    @include('helpers.form_control.control', [
                                        'type' => 'number',
                                        'name' => 'plan[options][email_max]',
                                        'value' => $options['email_max'],
                                        'label' => trans('messages.max_emails'),
                                        'attributes' => [
                                            'default-value' => '10000',
                                        ],
                                    ])
                                </div>
                                <div class="pt-3">
                                    <div class="pt-2">
                                        @include('helpers.form_control.control', [
                                            'type' => 'checkbox',
                                            'name' => 'unlimited',
                                            'value' => '',
                                            'label' => trans('messages.unlimited'),
                                            'attributes' => [
                                                'checked' => $options['email_max'] == -1,
                                            ],
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 unlimited_control">
                            <div class="d-flex align-items-center">
                                <div class="me-4" style="width:400px">
                                    @include('helpers.form_control.control', [
                                        'type' => 'number',
                                        'name' => 'plan[options][list_max]',
                                        'value' => $options['list_max'],
                                        'label' => trans('messages.max_lists'),
                                        'attributes' => [
                                            'default-value' => '100',
                                        ],
                                    ])
                                </div>
                                <div class="pt-3">
                                    <div class="pt-2">
                                        @include('helpers.form_control.control', [
                                            'type' => 'checkbox',
                                            'name' => 'unlimited',
                                            'value' => '',
                                            'label' => trans('messages.unlimited'),
                                            'attributes' => [
                                                'checked' => $options['list_max'] == -1,
                                            ],
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 unlimited_control">
                            <div class="d-flex align-items-center">
                                <div class="me-4" style="width:400px">
                                    @include('helpers.form_control.control', [
                                        'type' => 'number',
                                        'name' => 'plan[options][subscriber_max]',
                                        'value' => $options['subscriber_max'],
                                        'label' => trans('messages.max_subscribers'),
                                        'attributes' => [
                                            'default-value' => '5000',
                                        ],
                                    ])
                                </div>
                                <div class="pt-3">
                                    <div class="pt-2">
                                        @include('helpers.form_control.control', [
                                            'type' => 'checkbox',
                                            'name' => 'unlimited',
                                            'value' => '',
                                            'label' => trans('messages.unlimited'),
                                            'attributes' => [
                                                'checked' => $options['subscriber_max'] == -1,
                                            ],
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 unlimited_control">
                            <div class="d-flex align-items-center">
                                <div class="me-4" style="width:400px">
                                    @include('helpers.form_control.control', [
                                        'type' => 'number',
                                        'name' => 'plan[options][subscriber_per_list_max]',
                                        'value' => $options['subscriber_per_list_max'],
                                        'label' => trans('messages.max_subscribers_per_list'),
                                        'attributes' => [
                                            'default-value' => '1000',
                                        ],
                                    ])
                                </div>
                                <div class="pt-3">
                                    <div class="pt-2">
                                        @include('helpers.form_control.control', [
                                            'type' => 'checkbox',
                                            'name' => 'unlimited',
                                            'value' => '',
                                            'label' => trans('messages.unlimited'),
                                            'attributes' => [
                                                'checked' => $options['subscriber_per_list_max'] == -1,
                                            ],
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 unlimited_control">
                            <div class="d-flex align-items-center">
                                <div class="me-4" style="width:400px">
                                    @include('helpers.form_control.control', [
                                        'type' => 'number',
                                        'name' => 'plan[options][segment_per_list_max]',
                                        'value' => $options['segment_per_list_max'],
                                        'label' => trans('messages.segment_per_list_max'),
                                        'attributes' => [
                                            'default-value' => '10',
                                        ],
                                    ])
                                </div>
                                <div class="pt-3">
                                    <div class="pt-2">
                                        @include('helpers.form_control.control', [
                                            'type' => 'checkbox',
                                            'name' => 'unlimited',
                                            'value' => '',
                                            'label' => trans('messages.unlimited'),
                                            'attributes' => [
                                                'checked' => $options['segment_per_list_max'] == -1,
                                            ],
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 unlimited_control">
                            <div class="d-flex align-items-center">
                                <div class="me-4" style="width:400px">
                                    @include('helpers.form_control.control', [
                                        'type' => 'number',
                                        'name' => 'plan[options][campaign_max]',
                                        'value' => $options['campaign_max'],
                                        'label' => trans('messages.max_campaigns'),
                                        'attributes' => [
                                            'default-value' => '500',
                                        ],
                                    ])
                                </div>
                                <div class="pt-3">
                                    <div class="pt-2">
                                        @include('helpers.form_control.control', [
                                            'type' => 'checkbox',
                                            'name' => 'unlimited',
                                            'value' => '',
                                            'label' => trans('messages.unlimited'),
                                            'attributes' => [
                                                'checked' => $options['campaign_max'] == -1,
                                            ],
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 unlimited_control">
                            <div class="d-flex align-items-center">
                                <div class="me-4" style="width:400px">
                                    @include('helpers.form_control.control', [
                                        'type' => 'number',
                                        'name' => 'plan[options][automation_max]',
                                        'value' => $options['automation_max'],
                                        'label' => trans('messages.max_automations'),
                                        'attributes' => [
                                            'default-value' => '200',
                                        ],
                                    ])
                                </div>
                                <div class="pt-3">
                                    <div class="pt-2">
                                        @include('helpers.form_control.control', [
                                            'type' => 'checkbox',
                                            'name' => 'unlimited',
                                            'value' => '',
                                            'label' => trans('messages.unlimited'),
                                            'attributes' => [
                                                'checked' => $options['automation_max'] == -1,
                                            ],
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 unlimited_control">
                            <div class="d-flex align-items-center">
                                <div class="me-4" style="width:400px">
                                    @include('helpers.form_control.control', [
                                        'type' => 'number',
                                        'name' => 'plan[options][max_size_upload_total]',
                                        'value' => $options['max_size_upload_total'],
                                        'label' => trans('messages.max_size_upload_total'),
                                        'attributes' => [
                                            'default-value' => '1000',
                                        ],
                                    ])
                                </div>
                                <div class="pt-3">
                                    <div class="pt-2">
                                        @include('helpers.form_control.control', [
                                            'type' => 'checkbox',
                                            'name' => 'unlimited',
                                            'value' => '',
                                            'label' => trans('messages.unlimited'),
                                            'attributes' => [
                                                'checked' => $options['max_size_upload_total'] == -1,
                                            ],
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 unlimited_control">
                            <div class="d-flex align-items-center">
                                <div class="me-4" style="width:400px">
                                    @include('helpers.form_control.control', [
                                        'type' => 'number',
                                        'name' => 'plan[options][max_file_size_upload]',
                                        'value' => $options['max_file_size_upload'],
                                        'label' => trans('messages.max_file_size_upload'),
                                        'attributes' => [
                                            'default-value' => '50',
                                        ],
                                    ])
                                </div>
                                <div class="pt-3">
                                    <div class="pt-2">
                                        @include('helpers.form_control.control', [
                                            'type' => 'checkbox',
                                            'name' => 'unlimited',
                                            'value' => '',
                                            'label' => trans('messages.unlimited'),
                                            'attributes' => [
                                                'checked' => $options['max_file_size_upload'] == -1,
                                            ],
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="boxing">
                        @include('helpers.form_control', [
                            'type' => 'checkbox2',
                            'class' => '',
                            'name' => 'plan[options][unsubscribe_url_required]',
                            'value' => $options['unsubscribe_url_required'],
                            'label' => trans('messages.unsubscribe_url_required'),
                            'options' => ['no','yes'],
                            'help_class' => 'plan',
                            'rules' => $plan->resourcesRules()
                        ])
                    </div>
                    <div class="boxing">
                        @include('helpers.form_control', ['type' => 'checkbox2',
                            'class' => '',
                            'name' => 'plan[options][access_when_offline]',
                            'value' => $options['access_when_offline'],
                            'label' => trans('messages.access_when_offline'),
                            'options' => ['no','yes'],
                            'help_class' => 'plan',
                            'rules' => $plan->resourcesRules()
                        ])
                    </div>
                    <div class="boxing">
                        @include('helpers.form_control', ['type' => 'checkbox2',
                            'class' => '',
                            'name' => 'plan[options][list_import]',
                            'value' => $options['list_import'],
                            'label' => trans('messages.can_import_list'),
                            'options' => ['no','yes'],
                            'help_class' => 'plan',
                            'rules' => $plan->resourcesRules()
                        ])
                    </div>
                    <div class="boxing">
                        @include('helpers.form_control', ['type' => 'checkbox2',
                            'class' => '',
                            'name' => 'plan[options][list_export]',
                            'value' => $options['list_export'],
                            'label' => trans('messages.can_export_list'),
                            'options' => ['no','yes'],
                            'help_class' => 'plan',
                            'rules' => $plan->resourcesRules()
                        ])
                    </div>
                    <div class="boxing">
                        @include('helpers.form_control', ['type' => 'checkbox2',
                            'class' => '',
                            'name' => 'plan[options][api_access]',
                            'value' => $options['api_access'],
                            'label' => trans('messages.can_use_api'),
                            'options' => ['no','yes'],
                            'help_class' => 'plan',
                            'rules' => $plan->resourcesRules()
                        ])
                    </div>
                </div>
                <button class="btn btn-secondary me-2">{{ trans('messages.save') }}</button>
                <a href="{{ action('Admin\PlanController@index') }}" role="button" class="btn btn-link">
                    {{ trans('messages.cancel') }}
                </a>
            </div>
        </div>
    </form>
    
    <script>
        $(function() {
            var manager = new GroupManager();

            $('.unlimited_controls .unlimited_control').each(function() {
                manager.add({
                    textBox: $(this).find('input[type=text],input[type=number]'),
                    unlimitedCheck: $(this).find('input[type=checkbox]'),
                    defaultValue: $(this).find('input[type=text],input[type=number]').attr('default-value'),
                    currentValue: $(this).find('input[type=text],input[type=number]').val()
                });
            });

            manager.bind(function(group) {
                var doCheck = function() {
                    var checked = group.unlimitedCheck.is(':checked');
                    
                    if (checked) {
                        group.currentValue = group.textBox.val();
                        group.textBox.val(-1);
                        group.textBox.addClass("text-trans");
                        group.textBox.attr("readonly", "readonly");
                    } else {
                        if(group.textBox.val() == "-1") {
                            if (group.currentValue != "-1") {
                                group.textBox.val(group.currentValue);
                            } else {
                                group.textBox.val(group.defaultValue);
                            }
                        }
                        group.textBox.removeClass("text-trans");
                        group.textBox.removeAttr("readonly", "readonly");
                    }
                };

                group.unlimitedCheck.on('change', function() {
                    doCheck();
                });

                doCheck();
            });
        });
    </script>
@endsection
