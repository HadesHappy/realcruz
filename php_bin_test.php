<?php

try {
    // to prevent it that the output is compressed, resulting in invalid string
    ini_set('zlib.output_compression', 'Off');
} catch (\Exception $e) {
    // just ignore it
}

$valid = true;

if (!version_compare(PHP_VERSION, '7.3.0', '>=')) {
    echo "ERROR: PHP CLI 7.3.0 or higher is required.<br />";
    $valid = false;
}

//if (!empty(ini_get('open_basedir'))) {
//    echo "ERROR: Please disable the <strong>open_basedir</strong> setting to continue.<br />";
//    $valid = false;
//}

if (!function_exists('mysqli_connect')) {
    echo "ERROR: Mysqli Extension is required.<br />";
    $valid = false;
}
if (!extension_loaded('openssl')) {
    echo "ERROR: OpenSSL PHP Extension is required.<br />";
    $valid = false;
}
if (!extension_loaded('mbstring')) {
    echo "ERROR: Mbstring PHP Extension is required.<br />";
    $valid = false;
}
if (!extension_loaded('pdo')) {
    echo "ERROR: PDO PHP extension is required.<br />";
    $valid = false;
}
if (!extension_loaded('tokenizer')) {
    echo "ERROR: Tokenizer PHP Extension is required.<br />";
    $valid = false;
}
if (!class_exists('ZipArchive', false)) {
    echo "ERROR: PHP Zip Archive is required.<br />";
    $valid = false;
}
if (!extension_loaded('imap')) {
    echo "ERROR: PHP IMAP Extension is required.<br />";
    $valid = false;
}

/*** not required for PHP CLI
if (!(extension_loaded('gd') && function_exists('gd_info'))) {
    echo "ERROR: PHP GD Library is required.<br />";
    $valid = false;
}
***/

/*** not required for PHP CLI
if (!extension_loaded('fileinfo')) {
    echo "ERROR: PHP Fileinfo extension is required.<br />";
    $valid = false;
}
***/

if (!extension_loaded('curl')) {
    echo "ERROR: PHP CURL extension is required.<br />";
    $valid = false;
}
if (!extension_loaded('xml')) {
    echo "ERROR: PHP XML extension is required.<br />";
    $valid = false;
}
if (!class_exists('SQLite3')) {
    echo "ERROR: PHP SQLite3 extension is required.<br />";
    $valid = false;
}

/*
// proc_close() check =========
$proc_close_enabled = true;
try {
    $disabled = explode(',', ini_get('disable_functions'));
    $proc_close_enabled = !in_array('proc_close', $disabled);
} catch (\Exception $ex) {
    $proc_close_enabled = false;
}
if (!$proc_close_enabled) {
    echo "ERROR: <strong>proc_close()</strong> must be enabled.<br />";
    $valid = false;
}
*/
// =============================
// escapeshellarg() check =========
$escapeshellarg_enabled = true;
try {
    $disabled = explode(',', ini_get('disable_functions'));
    $escapeshellarg_enabled = !in_array('escapeshellarg', $disabled);
} catch (\Exception $ex) {
    $escapeshellarg_enabled = false;
}
if (!$escapeshellarg_enabled) {
    echo "ERROR: <strong>escapeshellarg()</strong> must be enabled.<br />";
    $valid = false;
}

// check directory permission
$dirs = [ 'storage/app', 'storage/framework', 'storage/logs', 'bootstrap/cache' ];
foreach ($dirs as $dir) {
    $basepath = pathinfo(__FILE__)['dirname'];
    $path = "$basepath/$dir";
    file_put_contents('/tmp/emo', $path);
    if (!(file_exists($path) && is_dir($path) && is_writable($path))) {
        echo "ERROR: The directory [{$dir}] must be writable by the web server.<br />";
        $valid = false;
    }
}

// just finish
if ($valid) {
    echo "ok";
}
