<?php

// check if a function is disable
function f_enabled($name)
{
    $disabled = preg_split('/[\s,]+/', ini_get('disable_functions'));
    return function_exists($name) && !in_array($name, $disabled);
}

// fucntions that are required by Acelle Mail
$required_functions = ['escapeshellarg', ];

// Check required functions
foreach ($required_functions as $f) {
    if (!f_enabled($f)) {
        echo "ERROR: The <strong>{$f}</strong> function is disabled on your hosting server. Please enable it to proceed. Contact your hosting provider if you are not sure how to do it.<br />";
        exit(0);
    }
}

// Check PHP version
if (!version_compare(PHP_VERSION, '7.3.0', '>=')) {
    echo "ERROR: Your current PHP version is ".PHP_VERSION.". However, Acelle requires PHP version 7.3.0 or higher.<br />";
    exit(0);
}

// Check required extensions
if (!extension_loaded('mbstring')) {
    echo "ERROR: The requested PHP Mbstring extension is missing from your system.<br />";
    exit(0);
}

// Check if open_basedir is in effect
//if (!empty(ini_get('open_basedir'))) {
//    echo "ERROR: Please disable the <strong>open_basedir</strong> setting to continue.<br />";
//    exit(0);
//}

// Check directory permission
if (!(file_exists('../storage/app') && is_dir('../storage/app') && (is_writable('../storage/app')))) {
    echo "ERROR: The directory [/storage/app] must be writable by the web server.<br />";
    exit(0);
}

if (!(file_exists('../storage/framework') && is_dir('../storage/framework') && (is_writable('../storage/framework')))) {
    echo "ERROR: The directory [/storage/framework] must be writable by the web server.<br />";
    exit(0);
}

if (!(file_exists('../storage/logs') && is_dir('../storage/logs') && (is_writable('../storage/logs')))) {
    echo "ERROR: The directory [/storage/logs] must be writable by the web server.<br />";
    exit(0);
}

if (!(file_exists('../bootstrap/cache') && is_dir('../bootstrap/cache') && (is_writable('../bootstrap/cache')))) {
    echo "ERROR: The directory [/bootstrap/cache] must be writable by the web server.<br />";
    exit(0);
}

require 'main.php';
