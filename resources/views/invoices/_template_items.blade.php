<table>

    <tr class="heading">
        <td>{{ trans('messages.invoice.items') }}</td>

        <td>{{ trans('messages.invoice.price') }}</td>
    </tr>

    
        @foreach ($bill['bill'] as $item)
            <tr class="item">
                <td>
                    <p class="mb-0"><strong><i>{{ $item['title'] }}</i></strong></p>
                    <p class="mb-0">{!! $item['description'] !!}</p>
                </td>

                <td>
                    {{ $item['price'] }}
                </td>
            </tr>
        @endforeach

    
    <tr class="total">
        <td></td>

        <td style="padding-right:0">
            <table>
                <tr>
                    <td style="text-align: right;border-bottom: solid 1px #ddd;">{{ trans('messages.bill.subtotal') }}:</td>
                    <td style="border-bottom: solid 1px #ddd;font-weight: normal;">{{ $bill['sub_total'] }}</td>
                </tr>
                <tr>
                    <td style="text-align: right;border-bottom: solid 1px #ddd;">{{ trans('messages.bill.tax') }}:</td>
                    <td style="border-bottom: solid 1px #ddd;font-weight: normal;">{{ $bill['tax'] }}</td>
                </tr>
                <tr>
                    <td style="text-align: right;border-bottom: solid 1px #ddd;">{{ trans('messages.bill.total') }}:</td>
                    <td style="border-bottom: solid 1px #ddd;">{{ $bill['total'] }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>