<?php

namespace Acelle\Library;

use Exception;

class Downloader
{
    protected $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function downloadTo($path)
    {
        $downloadUrl = $this->resolveUrl($this->url);
        set_time_limit(0);
        $fp = fopen($path, 'wa+');
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $downloadUrl);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_TIMEOUT, 3600);
        curl_setopt($curlSession, CURLOPT_FILE, $fp);
        curl_setopt($curlSession, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($curlSession);

        $error = curl_error($curlSession);
        curl_close($curlSession);
        fclose($fp);

        if (!empty($error)) {
            throw new Exception("Error downloading upgrade package: " . $error);
        }
    }

    private function resolveUrl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // follow redirects
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // set referer on redirect
        curl_exec($ch);
        $target = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
        if ($target) {
            return $target;
        } else {
            throw new Exception('Cannot resolve URL: '.$url);
        }
    }
}
