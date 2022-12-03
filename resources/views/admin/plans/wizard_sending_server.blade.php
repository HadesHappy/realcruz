@extends('layouts.popup.medium')

@section('bar-title')
{{ trans('messages.plan.new_plan') }}
@endsection

@section('content')
    <form id="wizardSendingServer" enctype="multipart/form-data" action="{{ action('Admin\PlanController@wizardSendingServer', $plan->uid) }}" method="POST" class="form-validate-jqueryx">
        {{ csrf_field() }}
        
        <div class="row">
            <div class="col-md-12">                    
                <h2 class="mt-0 mb-3">{{ trans('messages.plan.sending_server') }}</h2>
                    
                <p>{{ trans('messages.plan.sending_server.intro') }}</p>
                
                <div class="form-group control-radio">
                    <div class="radio_box" data-popup='tooltip' title="">
                        <label class="main-control">
                            <input {{ ($plan->getOption('sending_server_option') == \Acelle\Model\Plan::SENDING_SERVER_OPTION_SYSTEM ? 'checked' : '') }} type="radio"
                                name="plan[options][sending_server_option]"
                                value="{{ \Acelle\Model\Plan::SENDING_SERVER_OPTION_SYSTEM }}" class="styled" /> <rtitle>{{ trans('messages.plan_option.system_s_sending_server') }}</rtitle>
                            <div class="desc text-normal mb-10">
                                {{ trans('messages.plan_option.system_s_sending_server.intro') }}
                            </div>
                        </label>
                        <div class="radio_more_box">
                            
                        </div>
                    </div>
                    <hr>
                    <div class="radio_box" data-popup='tooltip' title="">
                        <label class="main-control">
                            <input {{ ($plan->getOption('sending_server_option') == \Acelle\Model\Plan::SENDING_SERVER_OPTION_OWN ? 'checked' : '') }} type="radio"
                                name="plan[options][sending_server_option]"
                                value="{{ \Acelle\Model\Plan::SENDING_SERVER_OPTION_OWN }}" class="styled" /> 
                                    <rtitle>{{ trans('messages.plan_option.own_sending_server') }} </rtitle>
                            <div class="desc text-normal mb-10">
                                {{ trans('messages.plan_option.own_sending_server.intro') }}
                            </div>
                        </label>
                        <div class="radio_more_box">
                            <div class="mb-3 unlimited_control">
                                <div class="d-flex align-items-center">
                                    <div class="me-4" style="width:400px">
                                        @include('helpers.form_control.control', [
                                            'type' => 'text',
                                            'name' => 'plan[options][sending_servers_max]',
                                            'value' => $plan->getOption('sending_servers_max'),
                                            'label' => trans('messages.max_sending_servers'),
                                            'help_class' => 'plan',
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
                                                    'checked' => $plan->getOption('sending_servers_max') == -1,
                                                ],
                                            ])
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <p>
                                @include('helpers.form_control', ['type' => 'checkbox2',
                                    'class' => '',
                                    'name' => 'plan[options][all_sending_server_types]',
                                    'value' => $plan->getOption('all_sending_server_types'),
                                    'label' => trans('messages.allow_adding_all_sending_server_types'),
                                    'options' => ['no','yes'],
                                    'help_class' => 'plan',
                                    'rules' => $plan->validationRules()['options'],
                                ])
                            </p>
                            <div class="all_sending_server_types_no">
                                <hr>
                                <label class="text-semibold text-muted">{{ trans('messages.select_allowed_sending_server_types') }}</label>
                                <div class="row">
                                    @foreach (Acelle\Model\SendingServer::types() as $key => $type)
                                        <div class="col-md-4 pt-2 d-flex align-items-center">
                                            <span class="notoping pull-left">
                                                @include('helpers.form_control', ['type' => 'checkbox',
                                                    'class' => '',
                                                    'name' => 'plan[options][sending_server_types][' . $key . ']',
                                                    'value' => isset($plan->getOption('sending_server_types')[$key]) ? $plan->getOption('sending_server_types')[$key] : 'no',
                                                    'label' => '',
                                                    'options' => ['no','yes'],
                                                    'help_class' => 'plan',
                                                    'rules' => $plan->validationRules()['options'],
                                                ])
                                            </span>
                                            &nbsp;&nbsp;<span class="text-semibold text-italic">{{ trans('messages.' . $key) }}</span>
                                            
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="radio_box" data-popup='tooltip' title="">
                        <label class="main-control">
                            <input {{ ($plan->getOption('sending_server_option') == \Acelle\Model\Plan::SENDING_SERVER_OPTION_SUBACCOUNT ? 'checked' : '') }} type="radio"
                                name="plan[options][sending_server_option]"
                                value="{{ \Acelle\Model\Plan::SENDING_SERVER_OPTION_SUBACCOUNT }}" class="styled" />  <rtitle>{{ trans('messages.plan_option.sub_account') }}</rtitle>
                            <div class="desc text-normal mb-10">
                                {{ trans('messages.plan_option.sub_account.intro') }}
                            </div>
                        </label>
                        <div class="radio_more_box">
                            @if (Auth()->user()->admin->getSubaccountSendingServers()->count())
                                <div class="row">
                                    <div class="col-md-6">
                                        @include('helpers.form_control', [
                                            'type' => 'select',
                                            'class' => 'numeric',
                                            'name' => 'plan[options][sending_server_subaccount_uid]',
                                            'value' => $plan->getOption('sending_server_subaccount_uid'),
                                            'label' => '',
                                            'help_class' => 'plan',
                                            'include_blank' => trans('messages.select_sending_server_with_subaccount'),
                                            'options' => Auth()->user()->admin->getSubaccountSendingServersSelectOptions(),
                                            'rules' => $plan->validationRules()['options'],
                                        ])
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    {!! trans('messages.plan_option.there_no_subaccount_sending_server') !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <button onClick="$('#wizard').submit();" class="btn btn-secondary me-2">{{ trans('messages.plan.wizard.finish') }}</button>
            <a href="javascript:;" onclick="PlansIndex.getWizardPopup().hide()" class="btn btn-link me-2" data-dismiss="modal">{{ trans('messages.plan.wizard.cancel') }}</a>
        </div>
        
    </form>

    <script>
        var ChangeTypePopup;

        $(function() {
            var manager = new GroupManager();

            $('.unlimited_control').each(function() {
                manager.add({
                    textBox: $(this).find('input[type=text]'),
                    unlimitedCheck: $(this).find('input[type=checkbox]'),
                    defaultValue: $(this).find('input[type=text]').attr('default-value'),
                    currentValue: $(this).find('input[type=text]').val()
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
    
    <script>
        $(document).ready(function() {
            // all sending servers checking
            $(document).on("change", "input[name='plan[options][all_sending_servers]']", function(e) {
                if($("input[name='plan[options][all_sending_servers]']:checked").length) {
                    $(".sending-servers").find("input[type=checkbox]").each(function() {
                        if($(this).is(":checked")) {
                            $(this).parents(".form-group").find(".switchery").eq(1).click();
                        }
                    });
                    $(".sending-servers").hide();
                } else {
                    $(".sending-servers").show();
                }
            });
            $("input[name='plan[options][all_sending_servers]']").trigger("change");
    
            // Sending domains checking setting
            $(document).on("change", "input[name='plan[options][create_sending_domains]']", function(e) {
                if($('input[name="plan[options][create_sending_domains]"]:checked').val() == 'yes') {
                    $(".sending-domains-yes").show();
                    $(".sending-domains-no").hide();
                } else {
                    $(".sending-domains-no").show();
                    $(".sending-domains-yes").hide();
                }
            });
            $('input[name="plan[options][create_sending_domains]"]').trigger("change");
    
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
        });
    </script>



    <script>
        $('#wizardSendingServer').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            
            // ajax load url
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                dataType: 'html',
            }).done(function(response) {
                if (response === 'success') {
                    window.location = '{{ action('Admin\PlanController@general', $plan->uid) }}';                
                } else {
                    PlansIndex.getWizardPopup().loadHtml(response);
                }
            });
            
            return false;
        });
    </script>
@endsection