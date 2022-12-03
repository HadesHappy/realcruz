<?php

namespace Acelle\Library\Everification;

use Exception;
use GuzzleHttp\Client;
use Acelle\Library\Exception\VerificationTakesLongerThanNormal;

class Emailable
{
    protected $options;
    protected $logger;

    public function __construct($options, $logger)
    {
        $this->options = $options;
        $this->logger = $logger;
    }

    public function verify($email)
    {
        // retrieve the service settings
        $client = new Client();

        // build the request URI
        $uri = "https://api.emailable.com/v1/verify?email={$email}&api_key={$this->options['api_key']}";

        // Request
        $response = $client->request('POST', $uri, [
            'headers' => ['Content-Type' => 'application/json'],
            'verify' => false,
        ]);

        $result = $this->parseResult($response);

        return [$result, $response];
    }

    public function parseResult($response)
    {
        // Get raw response
        $raw = (string)$response->getBody();

        // Verify result
        if (empty($raw)) {
            throw new Exception('EMPTY RESPONSE FROM VERIFICATION SERVICE: emailable.com');
        }

        // Convert raw response into json
        $json = json_decode($raw, true);

        if (!array_key_exists('state', $json)) {
            if (array_key_exists('message', $json)) {
                $this->logger->warning('Skipped. Server responds: '.$raw);
                throw new VerificationTakesLongerThanNormal($raw);
            } else {
                throw new Exception('Unexpected result from emailable.com: '.$raw);
            }
        }

        // Mapping
        $map = [
            'deliverable' => 'deliverable',
            'undeliverable' => 'undeliverable',
            'risky' => 'risky',
            'unknown' => 'unknown',
        ];

        if (!array_key_exists($json['state'], $map)) {
            throw new Exception('Unexpected "state" value from emailable.com: '.$raw);
        }

        return $map[$json['state']];
    }
}
