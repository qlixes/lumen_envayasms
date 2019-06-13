<?php

namespace qlixes\SmsGateway\Vendors;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class EnvayaSmsGateway extends Client
{
    private $options = [];

    function __construct()
    {
        parent::__construct(['base_uri' => config('smsgateway.uri')]);

        $this->token = config('smsgateway.token');

        $this->options['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => $this->token
        ];
    }

    function sms(array $destinations, string $text): ?array
    {
        $messages = [];
        foreach ($destinations as $destination) {
            $messages[] = [
                'phone_number' => $destination,
                'message'      => $text,
            ];
        };

        $this->options['json'] = $messages;

        $response = $this->request('POST', "gateway", $this->options);

        if($response->getStatusCode() != 200)
            Log::error($response->getReasonPhrase());

        return [
            'code' => $response->getStatusCode(),
            'message' => $response->getReasonPhrase(),
            'data' => json_decode($response->getBody()->getContents())
        ];
    }
}
