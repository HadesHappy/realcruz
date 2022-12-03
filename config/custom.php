<?php

return [
    // The standard format for storing date/time string
    // Used by Carbon and MySQL
    // Important: both `date_format` (for PHP) and `date_format_sql` (for MySQL) must produce the SAME output string
    'date_format' => 'Y-m-d',           // 2020-12-25
    'date_format_sql' => '%Y-%m-%d',    // 2020-12-25

    // Timeformat for storing
    'time_format' => 'H:i',

    // Minimum recommended PHP version
    // which is required by Laravel 8
    'php_recommended' => '7.3.0',

    // Minimum supported PHP version
    'php' => '7.3.0',

    'woo' => false,

    'japan' => false,
];
