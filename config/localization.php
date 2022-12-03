<?php

return [

    // Notice that any format key must  be present in the default '*' section
    // i.e. keys that are available in a specific locale like 'en', 'ja'...
    // but are not available in '*' shall be considered INVALID
    // Use the following methods to work with datetime
    //
    //    Customer::formatDateTime(Carbon $datetime, $name)
    //
    //    or, for pages that are not associated to a customer/user (login, registration, etc.):
    //
    //    format_datetime(Carbon $datetime, $name, $locale) || locale can be 'en', 'ja', etc.
    //

    '*' => [
        'date_full' => 'Y-m-d',
        'date_short' => 'Y-m-d',
        'datetime_full' => 'Y-m-d H:i',
        'datetime_short' => 'Y-m-d H:i',
        'time_only' => 'H:i',
        'number_precision' => '2',
        'number_decimal_separator' => '.',
        'number_thousands_separator' => ','
    ],

    'ja' => [
        'date_full' => 'Y年m月d日',
        'date_short' => 'Y/m/d',
        'datetime_full' => 'Y年m月d日 H:i',
        'datetime_short' => 'Y/m/d H:i',
        'time_only' => 'H:i',
    ],
];
