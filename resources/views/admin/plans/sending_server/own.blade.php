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
            <li class="breadcrumb-item"><a href="{{ action('Admin\PlanController@sendingServer', $plan->uid) }}">
                    {{ $plan->name }}
                </a>
            </li>
        </ul>
        <h1 class="mc-h1">
            <span class="text-semibold">{{ $plan->name }}</span>
        </h1>
    </div>

@endsection

@section('content')
    
    @include('admin.plans._menu')
    
    <form action="{{ action('Admin\PlanController@sendingServerOwn', $plan->uid) }}" method="POST" class="form-validate-jqueryx">
        {{ csrf_field() }}
        
        <div class="mc-section">
            <div class="announce_box">
                <div class="row flex-center">
                    <div class="col-md-8">
                        <span class="material-symbols-rounded announce_box-icon">
person_outline
</span>
                        <label>{{ trans('messages.plan_option.delivery_setting') }}</label>
                        <h4>{{ trans('messages.plan_option.own_sending_server') }}</h4>
                        <p>{{ trans('messages.plan_option.own_sending_server.intro') }}</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a class="btn btn-secondary mr-20 change-server-type-button" modal-size="lg"
                            href="{{ action('Admin\PlanController@sendingServerOption', [
                            'uid' => $plan->uid]) }}">
                                {{ trans('messages.plan_option.change') }}</a>
                    </div>
                </div>
            </div>
                
            <div class="row flex-center">
                <div class="col-md-12">
                    <h2 class="mc-h2 mt-0 mb-10">
                        <span class="text-semibold">{{ trans('messages.plan.sending_servers.own.setting') }}</span>
                    </h2>
                    <p>{{ trans('messages.plan.sending_servers.own.setting.intro') }}</p>
                </div>
            </div>

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
                <hr>
            </div>
        </div>
        <button class="btn btn-secondary me-2">{{ trans('messages.save') }}</button>
    </form>

    <script>
        var ChangeTypePopup;

        $(function() {
            $('.change-server-type-button').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                ChangeTypePopup = new Popup({
                    url: url
                });
                ChangeTypePopup.load();
            });

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
        var current_option = $("input[name='plan[options][sending_server_option]']:checked").val();
    
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
    
@endsection


