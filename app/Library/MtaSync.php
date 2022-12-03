<?php

namespace Acelle\Library;

use GuzzleHttp\Client;

class MtaSync
{
    public function __construct($apiEndpoint, $apiKey)
    {
        $this->apiEndpoint = $apiEndpoint;
        $this->apiKey = $apiKey;
    }

    private function getClient()
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $this->apiEndpoint,
            // You can set any number of default request options.
            'timeout' => 5.0,
        ]);

        return $client;
    }

    public function addDomain($name, $attributes = [])
    {
        $params = [
            'name' => $name,
            'host_verified' => false,
            'dkim_verified' => false,
            'spf_verified' => false,
            'added_by' => 'Unknown',
        ];

        $params = array_merge($params, $attributes);

        $this->request('POST', 'api/domain', $params);
    }

    public function removeDomain($name)
    {
        $params = [
            '_method' => 'DELETE',
        ];

        $this->request('DELETE', "api/domain/{$name}", $params);
    }

    public function getDomains($name)
    {
        $this->request('GET', 'api/domain');
    }

    private function request($type, $action, $params = [])
    {
        $response = $this->getClient()->request($type, $action, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$this->apiKey,
            ],
            'form_params' => $params,
        ]);
    }
}
