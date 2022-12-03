<?php

namespace Acelle\Helpers;

use Acelle\Library\StringHelper;
use Exception;
use File;
use Acelle\Library\Contracts\HasQuota as HasQuotaInterface;
use Closure;
use Carbon\Carbon;
use SimpleXMLElement;
use Mika56\SPFCheck\SPFCheck;
use Mika56\SPFCheck\DNSRecordGetterDirect;
use Mika56\SPFCheck\DNSRecordGetter;

function generatePublicPath($absPath, $withHost = false)
{
    // Notice: $relativePath must be relative to storage/ folder
    // For example, with a real path of /home/deploy/acellemail/storage/app/sub/example.png
    // then $relativePath should be "app/sub/example.png"

    if (empty(trim($absPath))) {
        throw new Exception('Empty path');
    }

    $excludeBase = storage_path();
    $pos = strpos($absPath, $excludeBase); // Expect pos to be exactly 0

    if ($pos === false) {
        throw new Exception(sprintf("File '%s' cannot be made public, only files under storage/ folder can", $absPath));
    }

    if ($pos != 0) {
        throw new Exception(sprintf("Invalid path '%s', cannot make it public", $absPath));
    }

    // Do not use string replace, as path parts may occur more than once
    // For example: abc/xyz/abc/xyz...
    $relativePath = substr($absPath, strlen($excludeBase) + 1);

    if ($relativePath === false) {
        throw new Exception("Invalid path {$absPath}");
    }

    $dirname = dirname($relativePath);
    $basename = basename($relativePath);
    $encodedDirname = StringHelper::base64UrlEncode($dirname);

    // If Laravel is under a subdirectory
    $subdirectory = getAppSubdirectory();

    if (empty($subdirectory) || $withHost) {
        // Return something like
        //     "http://localhost/{subdirectory if any}/p/assets/ef99238abc92f43e038efb"   # withHost = true, OR
        //     "/p/assets/ef99238abc92f43e038efb"                   # withHost = false
        $url = route('public_assets', [ 'dirname' => $encodedDirname, 'basename' => rawurlencode($basename) ], $withHost);
    } else {
        // Make sure the $subdirectory has a leading slash ('/')
        $subdirectory = join_paths('/', $subdirectory);
        $url = join_paths($subdirectory, route('public_assets', [ 'dirname' => $encodedDirname, 'basename' => $basename ], $withHost));
    }

    return $url;
}

function getAppSubdirectory()
{
    // IMPORTANT: do not use url('/') as it will not work correctly
    // when calling from another file (like filemanager/config/config.php for example)
    // Otherwise, it will always return 'http://localhost' --> without subdirectory
    $path = parse_url(config('app.url'), PHP_URL_PATH);
    $path = trim($path, '/');
    return empty($path) ? null : $path;
}

// Get application host with {scheme}://{host}:{port} (without subdirectory)
function getAppHost()
{
    $fullUrl = config('app.url');
    $meta = parse_url($fullUrl);

    if (!array_key_exists('scheme', $meta) || !array_key_exists('host', $meta)) {
        throw new Exception('Invalid app.url setting');
    }

    $appHost = "{$meta['scheme']}://{$meta['host']}";

    if (array_key_exists('port', $meta)) {
        $appHost = "{$appHost}:{$meta['port']}";
    }

    return $appHost;
}

function updateTranslationFile($targetFile, $sourceFile, $overwriteTargetPhrases = false, $sort = false)
{
    $source = include $sourceFile;
    $target = include $targetFile;

    if ($overwriteTargetPhrases) {
        // Overwrite $target
        $merged = $source + $target;
    } else {
        // Respect $target
        $merged = $target + $source;
    }

    // Find keys in $target that are that not available in $source
    $diff = array_diff_key($target, $source);

    // Delete those keys in the final result
    $merged = array_diff_key($merged, $diff);

    if ($sort) {
        ksort($merged);
    }

    $out = '<?php return '.var_export(\Yaml::parse(\Yaml::dump($merged)), true).' ?>';
    \File::put($targetFile, $out);
}

// Copy and:
// + Remove the destination first
// + Create parent folders if not exist
function pcopy($src, $dst)
{
    if (!File::exists($src)) {
        throw new Exception("File `{$src}` does not exist");
    }

    if (File::exists($dst)) {
        // Delete the file or link or directory
        if (is_link($dst) || is_file($dst)) {
            File::delete($dst);
        } else {
            File::deleteDirectory($dst);
        }
    } else {
        // Make sure the PARENT directory exists
        $dirname = pathinfo($dst)['dirname'];
        if (!File::exists($dirname)) {
            File::makeDirectory($dirname, 0777, true, true);
        }
    }

    // if source is a file, just copy it
    if (File::isFile($src)) {
        File::copy($src, $dst);
    } else {
        File::copyDirectory($src, $dst);
    }
}

function ptouch($filepath)
{
    $dirname = dirname($filepath);
    if (!File::exists($dirname)) {
        File::makeDirectory($dirname, 0777, true, true);
    }

    touch($filepath);
}

function xml_to_array(SimpleXMLElement $xml)
{
    $parser = function (SimpleXMLElement $xml, array $collection = []) use (&$parser) {
        $nodes = $xml->children();
        $attributes = $xml->attributes();

        if (0 !== count($attributes)) {
            foreach ($attributes as $attrName => $attrValue) {
                $collection['attributes'][$attrName] = html_entity_decode(strval($attrValue));
            }
        }

        if (0 === $nodes->count()) {
            // $collection['value'] = strval($xml);
            // return $collection;
            return html_entity_decode(strval($xml));
        }

        foreach ($nodes as $nodeName => $nodeValue) {
            if (count($nodeValue->xpath('../' . $nodeName)) < 2) {
                $collection[$nodeName] = $parser($nodeValue);
                continue;
            }

            $collection[$nodeName][] = $parser($nodeValue);
        }

        return $collection;
    };

    return [
        $xml->getName() => $parser($xml)
    ];
}

function spfcheck($ipOrHostname, $domain)
{
    $checker = new SPFCheck(new DNSRecordGetterDirect('8.8.8.8'));

    // $checker = new SPFCheck(new DNSRecordGetter());
    $result = $checker->isIPAllowed($ipOrHostname, $domain);

    if (SPFCheck::RESULT_PASS != $result) {
        // try again with another method
        $checker = new SPFCheck(new DNSRecordGetter());
        $result = $checker->isIPAllowed($ipOrHostname, $domain);
    }

    return $result;
}

function forceAddCustomerToUnlimitedPlan($customer)
{
    // Default subscription
    $subscription = new \Acelle\Model\Subscription();
    $subscription->status = \Acelle\Model\Subscription::STATUS_ACTIVE;
    $subscription->current_period_ends_at = \Carbon\Carbon::now()->addYears(1000);
    $subscription->plan_id = \Acelle\Model\Plan::UNLIMITED_PLAN_ID;
    $subscription->customer_id = $customer->id;
    $subscription->save();
}

function saasToSingleMode()
{
    // set env mode
    echo "1. Set APP_SAAS=false in .env file...";
    write_env('APP_SAAS', 'false');

    // remove all subscriptions
    echo "\n2. Remove all subscriptions...";
    foreach (\Acelle\Model\Subscription::all() as $subscription) {
        $subscription->delete();
    }

    // remove all plans
    echo "\n3. Remove all plans...";
    foreach (\Acelle\Model\Plan::all() as $plan) {
        $plan->delete();
    }

    // check if has unlimited plan
    echo "\n4. Create unlimited plan...";
    \Acelle\Model\Plan::createUnlimitedPlan();

    // add all customer to unlimited plan
    echo "\n5. Add all customers to unlimited plan...";
    foreach (\Acelle\Model\Customer::all() as $customer) {
        \Acelle\Helpers\forceAddCustomerToUnlimitedPlan($customer);
    }

    echo "\n...\ndone!";
}

function isValidPublicHostnameOrIpAddress($host)
{
    if ($host == '127.0.0.1' || $host == 'localhost') {
        return false;
    }

    $isValidIpAddress = filter_var($host, FILTER_VALIDATE_IP);
    $getHostByName = gethostbyname($host);

    if ($isValidIpAddress) {
        return true;
    } elseif (filter_var($getHostByName, FILTER_VALIDATE_IP)) {
        return true;
    } else {
        return false;
    }
}

function write_env($key, $value)
{
    // Important: make sure all variables are already loaded before retrieving $_ENV
    \Artisan::call('config:cache');

    // Get current env
    $outputs = $_ENV;

    // Set the value
    $outputs[$key] = $value;

    // Prepare for writing back to file
    array_walk($outputs, function (&$v, $k) {
        // Escape double quote
        $cleaned = addcslashes($v, '"');

        // Quote if there is at least one space or # or any suspected char!
        if (preg_match('/[\s\#!\$]/', $cleaned)) {
            $cleaned = "\"$cleaned\"";
        }

        $v = "$k=$cleaned";
    });

    $outputs = array_values($outputs);
    $outputs = implode("\n", $outputs);

    // Actually write to file .env
    file_put_contents(app()->environmentFilePath(), $outputs);

    // Important, make the new environment var available
    // Otherwise, this method may failed if called twice (in a loop for example) in the same process
    \Artisan::call('config:cache');
}

function write_envs($params)
{
    foreach ($params as $key => $value) {
        write_env($key, $value);
    }
}

function reset_app_url($force = false)
{
    $envs = getenv();
    if (!array_key_exists('APP_URL', $envs) || $force) {
        $url = url('/');
        write_env('APP_URL', $url);
    }
}
