<?php

namespace Acelle\Library\Lazada;

use Acelle\Http\Controllers\Controller;
use Acelle\Library\Lazada\Lazop\LazopClient;
use Acelle\Library\Lazada\Lazop\LazopRequest;
use Acelle\Library\Lazada\Lazop\UrlConstants;
use Acelle\Library\Lazada\Lazop\LazopLogger;
use Acelle\Library\Lazada\Lazop\Constants;

if (!defined("LAZOP_SDK_WORK_DIR")) {
    define("LAZOP_SDK_WORK_DIR", dirname(__FILE__));
}

if (!defined("LAZOP_AUTOLOADER_PATH")) {
    define("LAZOP_AUTOLOADER_PATH", dirname(__FILE__));
}

class LazadaConnection
{
    public $appKey;
    public $appSecret;
    public $service;
    public $data;
    public $endpoint="https://api.lazada.vn/rest";

    public function __construct($appKey=false, $appSecret=false, $data=[])
    {
        $this->appKey = config('lazada.key');
        $this->appSecret = config('lazada.secret');

        if ($appKey) {
            $this->appKey = $appKey;
        }
        if ($appKey) {
            $this->appSecret = $appSecret;
        }
        if ($data) {
            $this->data = $data;
        }
    }

    public function getConnectLink()
    {
        return "https://auth.lazada.com/oauth/authorize?response_type=code&force_auth=true&redirect_uri=" . action('SourceController@connect') . "&client_id=" . $this->appKey;
    }

    public function getAccessToken($code)
    {
        $this->service = new LazopClient("https://auth.lazada.com/rest", $this->appKey, $this->appSecret);

        $re = new LazopRequest('/auth/token/create');
        $re->addApiParam('code', $code);
        $res = $this->service->execute($re);

        $this->data = json_decode($res, true);

        return $this->data['access_token'];
    }

    public function refreshToken()
    {
        $this->service = new LazopClient("https://auth.lazada.com/rest", $this->appKey, $this->appSecret);

        $re = new LazopRequest('/auth/token/refresh');
        $re->addApiParam('refresh_token', $this->data['refresh_token']);

        $res = $this->service->execute($re);

        $this->data = json_decode($res, true);
    }

    public function makeRequest($url, $params, $method='GET')
    {
        $this->service = new LazopClient("https://api.lazada.vn/rest", $this->appKey, $this->appSecret);

        $request = new LazopRequest($url, $method);

        foreach ($params as $key => $value) {
            $request->addApiParam($key, $value);
        }

        if (isset($this->data['access_token'])) {
            return json_decode($this->service->execute($request, $this->data['access_token']), true);
        } else {
            return json_decode($this->service->execute($request), true);
        }
    }

    public function getProducts($options=[])
    {
        return $this->makeRequest('/products/get', $options);
    }

    public function getBrands()
    {
        return $this->makeRequest('/category/brands/query', [
            'startRow' => '0',
            'pageSize' => '20',
        ]);
    }
}
