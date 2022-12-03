@extends('layouts.core.backend')

@section('title', $plan->name)

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/js/editor.js') }}"></script>
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
        {{ csrf_field() }}
        
        <div class="row">
            <div class="col-md-7">
                <div class="mc_section">
                    <h2>{{ trans('messages.plan.email_footer') }}</h2>
                        
                    <p>{{ trans('messages.plan.email_footer.intro') }}</p>
                        
                    <div class="form-group-checkboxes">                        
                        @include('helpers.form_control', [
                            'class' => '',
                            'type' => 'checkbox2',
                            'name' => 'plan[options][email_footer_enabled]',
                            'label' => trans('messages.plan.enabled_email_footer'),
                            'value' => $plan->getOption('email_footer_enabled'),
                            'options' => ['no','yes'],
                            'help_class' => 'plan',
                            'rules' => $plan->validationRules()['options'],
                        ])
                        
                        @include('helpers.form_control', [
                            'class' => '',
                            'type' => 'checkbox2',
                            'name' => 'plan[options][email_footer_trial_period_only]',
                            'label' => trans('messages.plan.email_footer_trial_period_only'),
                            'value' => $plan->getOption('email_footer_trial_period_only'),
                            'options' => ['no','yes'],
                            'help_class' => 'plan',
                            'rules' => $plan->validationRules()['options'],
                        ])
                    </div>
                    
                    @include('helpers.form_control', [
                        'class' => 'setting-editor',
                        'type' => 'textarea',
                        'name' => 'plan[options][html_footer]',
                        'label' => trans('messages.plan.html_footer'),
                        'value' => $plan->getOption('html_footer'),
                        'help_class' => 'plan',
                        'rules' => $plan->validationRules()['options'],
                    ])
                    
                    @include('helpers.form_control', [
                        'class' => '',
                        'type' => 'textarea',
                        'name' => 'plan[options][plain_text_footer]',
                        'label' => trans('messages.plan.plain_text_footer'),
                        'value' => $plan->getOption('plain_text_footer'),
                        'help_class' => 'plan',
                        'rules' => $plan->validationRules()['options'],
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
        $(document).ready(function() {
            // Sending domains checking setting
            $(document).on("change", "input[name='plan[options][email_footer_enabled]']", function(e) {
                var email_footer_enabled = $('input[name="plan[options][email_footer_enabled]"]:checked').val();
                
                if (email_footer_enabled == 'yes') {
                    $('input[name="plan[options][email_footer_trial_period_only]"]').closest('.checkbox').removeClass('disabled');
                    $('input[name="plan[options][email_footer_trial_period_only]"]').removeAttr('disabled');
                    tinymce.activeEditor.setMode('design');
                    $('[name="plan[options][plain_text_footer]"]').prop('readonly', false);
                } else {
                    $('input[name="plan[options][email_footer_trial_period_only]"]').closest('.checkbox').addClass('disabled');
                    $('input[name="plan[options][email_footer_trial_period_only]"]').attr('disabled', 'disabled');
                    tinymce.activeEditor.setMode('readonly');
                    $('[name="plan[options][plain_text_footer]"]').prop('readonly', true);
                }
            });
            setTimeout(function() {
                $('input[name="plan[options][email_footer_enabled]"]').trigger("change");
            }, 1000);
    
            // all email verification servers checking
            $(document).on("change", "input[name='plan[options][all_email_verification_servers]']", function(e) {
                if($("input[name='plan[options][all_email_verification_servers]']:checked").length) {
                    $(".email-verification-servers").find("input[type=checkbox]").each(function() {
                        if($(this).is(":checked")) {
                            $(this).parents(".form-group").find(".switchery").eq(1).click();
                        }
                    });
                    $(".email-verification-servers").hide();
                } else {
                    $(".email-verification-servers").show();
                }
            });
            $("input[name='plan[options][all_email_verification_servers]']").trigger("change");
    
    
            // Email verification servers checking setting
            $(document).on("change", "input[name='plan[options][create_email_verification_servers]']", function(e) {
                if($('input[name="plan[options][create_email_verification_servers]"]:checked').val() == 'yes') {
                    $(".email-verification-servers-yes").show();
                    $(".email-verification-servers-no").hide();
                } else {
                    $(".email-verification-servers-no").show();
                    $(".email-verification-servers-yes").hide();
                }
            });
            $('input[name="plan[options][create_email_verification_servers]"]').trigger("change");
    
            // Sending servers type checking setting
            $(document).on("change", "input[name='plan[options][all_sending_server_types]']", function(e) {
                if($('input[name="plan[options][all_sending_server_types]"]:checked').val() == 'yes') {
                    $(".all_sending_server_types_yes").show();
                    $(".all_sending_server_types_no").hide();
                } else {
                    $(".all_sending_server_types_no").show();
                    $(".all_sending_server_types_yes").hide();
                }
            });
            $('input[name="plan[options][all_sending_server_types]"]').trigger("change");
            
            // Sending servers type checking setting
            $(document).on("change", "input[name='plan[options][sending_server_option]']", function(e) {
                // Finding modal
                var modal = $('#confirm-modal');
                
                modal.find('.modal-body h6').html('{{ trans('messages.plan.sending_server.change_confirm') }}');
                modal.find('.modal-footer .link-confirm-button').attr("onclick", "$('#planSendingServerSetting').submit()");
                
                modal.modal("show");
            });
        });
    </script>
        
@endsection
