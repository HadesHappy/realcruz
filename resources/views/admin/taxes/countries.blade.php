@if (!count(Acelle\Model\Setting::getTaxSettings()['countries']))
    <div class="alert alert-info mt-4">
        {{ trans('messages.tax.no_country_added') }}
    </div>
@else
    <table class="table mt-4">
        <thead class="table-light">
            <tr>
                <th>{{ trans('messages.country') }}</th>
                <th>{{ trans('messages.tax_rate') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach(Acelle\Model\Setting::getTaxSettings()['countries'] as $code => $rate)
                <tr>
                    <td>
                        {{ \Acelle\Model\Country::findByCode($code)->name }}
                    </td>
                    <td>{{ $rate }}%</td>
                    <td>
                        <a href="javascript:;" data-value="{{ \Acelle\Model\Country::findByCode($code)->id }}"
                                class="edit-country-tax">
                            {{ trans('messages.edit') }}
                        </a>
                        |
                        <a href="{{ action('Admin\TaxController@removeCountry', [
                            'code' => $code
                        ]) }}"
                            class="remove-country-tax">
                            {{ trans('messages.remove') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        var TaxesCountries = {
            removeTax: function(url) {
                new Dialog('confirm', {
                    message: '{{ trans('messages.tax.remove_confirm') }}',
                    ok: function() {
                        //
                        addMaskLoading();

                        $.ajax({
                            method: "POST",
                            url: url,
                            data: {
                                _token: CSRF_TOKEN,
                            }
                        })
                        .done(function( res ) {
                            removeMaskLoading();

                            notify({
                                type: res.status,
                                message: res.message
                            });

                            TaxesSettings.getCountryTaxesBox().load();
                        });
                    }
                });
            }
        }

        $(function() {
            $('.edit-country-tax').on('click', function(e) {
                e.preventDefault();
                var country_id = $(this).attr('data-value');
                TaxesSettings.loadCountryTaxPopup(country_id);
            });

            $('.remove-country-tax').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                TaxesCountries.removeTax(url);
            });
        })
    </script>
@endif