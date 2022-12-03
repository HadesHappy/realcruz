@extends('layouts.core.backend')

@section('title', trans('messages.templates'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">                
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">
                payments
                </span> {{ trans('messages.tax_settings') }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <form id="TaxSettingsForm" action="{{ action('Admin\PlanController@wizard') }}" method="POST">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <div class="d-flex">
                        <div class="me-3">
                            <label class="m-0">
                                <input type="hidden" name="tax[enabled]"
                                    value="no" class="styled" />
                                <input type="checkbox" name="tax[enabled]"
                                    {{ Acelle\Model\Setting::getTaxSettings()['enabled'] == 'yes' ? 'checked' : '' }}
                                    value="yes" class="styled" />
                            </label>
                        </div>
                        <div>
                            <div class="fw-600">{{ trans('messages.tax.enable') }}</div>
                            <p class="mb-0">{{ trans('messages.tax.enable.intro') }}</p>
                        </div>
                    </div>
                    
                    <div class="tax-settings">
                        <div class="mt-4 d-flex align-items-center">
                            <div class="me-3">
                                <span>{{ trans('messages.tax.default_rate') }}</span>
                            </div>
                            <div class="form-group-mb-0 me-1" style="width:100px">
                                @include('helpers.form_control', [
                                    'type' => 'number',
                                    'name' => 'tax[default_rate]',
                                    'value' => Acelle\Model\Setting::getTaxSettings()['default_rate'],
                                    'help_class' => 'tax',
                                    'rules' => ['tax.default_rate' => 'required'],
                                ])
                            </div>
                            <div class="me-3">%</div>
                            <div>
                                <button type="button" class="btn btn-primary set-default-rate px-3">{{ trans('messages.tax.set') }}</button>
                            </div>
                        </div>

                        @if (!config('custom.japan'))
                            <hr>
                            <p class="mt-4">
                                {{ trans('messages.tax.country_taxes.intro') }}
                            </p>
                            <div class="mt-4 d-flex align-items-center">
                                <div class="me-5 fw-600">
                                    <span>{{ trans('messages.tax.by_country') }}</span>
                                </div>
                                <div class="form-group-mb-0 me-3" style="width:200px">
                                    @include('helpers.form_control', [
                                        'type' => 'select',
                                        'name' => 'country_id',
                                        'label' => '',
                                        'value' => '',
                                        'options' => Acelle\Model\Country::getSelectOptions(),
                                        'include_blank' => config('custom.japan') ? false : trans('messages.choose'),
                                    ])
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary add-country-tax px-3">{{ trans('messages.tax.country.add') }}</button>
                                </div>
                            </div>

                            <div class="country-taxes">
                                
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        var TaxesSettings = {
            countryTaxesBox: null,
            getCountryTaxesBox: function() {
                if (this.countryTaxesBox == null) {
                    this.countryTaxesBox = new Box($('.country-taxes'), '{{ action('Admin\TaxController@countries') }}');
                }
                return this.countryTaxesBox;
            },

            isValid: function() {
                var rate = $('[name="tax[default_rate]"]').val();

                if (rate == '') {
                    new Dialog('alert', {
                        message: '{{ trans('messages.tax.default_rate_required') }}'
                    });
                    return false;
                }

                return true;
            },

            setDefaultRate: function() {
                if (!this.isValid()) {
                    return;
                }

                addMaskLoading();
                
                $.ajax({
                    method: "POST",
                    url: '{{ action('Admin\TaxController@settings') }}',
                    data: $('#TaxSettingsForm').serialize()
                })
                .done(function( res ) {
                    removeMaskLoading();

                    notify({
                        type: res.status,
                        message: res.message
                    });
                });

                return true;
            },

            addTaxPopup: null,
            getAddTaxPopup: function() {
                if (this.addTaxPopup == null) {
                    this.addTaxPopup = new Popup({
                        url: '{{ action('Admin\TaxController@addTax') }}'
                    });
                }

                return this.addTaxPopup;
            },

            loadCountryTaxPopup: function(country_id) {
                this.getAddTaxPopup().options.data = {
                    country_id: country_id
                }

                this.getAddTaxPopup().load();
            }
        }

        $(function() {
            TaxesSettings.getCountryTaxesBox().load();

            // click set rate
            $('.set-default-rate').on('click', function() {
                TaxesSettings.setDefaultRate();
            });

            // enabled
            $('[name="tax[enabled]"]').on('click', function(e) {
                if (!TaxesSettings.isValid()) {
                    e.preventDefault(); 
                }
            });
            $('[name="tax[enabled]"]').on('change', function() {
                TaxesSettings.setDefaultRate();
            });

            // click add country tax
            $('.add-country-tax').on('click', function() {
                var country_id = $('[name=country_id').val();

                if (country_id == '') {
                    new Dialog('alert', {
                        message: '{{ trans('messages.tax.country_required') }}'
                    });
                    return;
                }
                
                TaxesSettings.loadCountryTaxPopup(country_id);
            });

            // toggle tax enabled
            var manager = new GroupManager();
            manager.add({
                checkbox: $('[name="tax[enabled]"]'),
                box: $('.tax-settings')
            });
            manager.bind(function(group) {
                group.check = function() {
                    if (group.checkbox.is(':checked')) {
                        group.box.show();
                    } else {
                        group.box.hide();
                    }
                }

                group.check();

                group.checkbox.on('change', function() {
                    group.check();
                });
            })
        });
    </script>
@endsection
