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
        $responses = [];

        foreach ($destinations as $destination) {
            $messages = [
                'user_phone' => $destination,
                'user_message'      => $text,
            ];

            $this->options['form_params'] = $messages;

            $response = $this->request("POST", "gateway", $this->options);

            if($response->getStatusCode() != 200)
                Log::error($response->getReasonPhrase());

            $responses[] = [
                'code' => $response->getStatusCode(),
                'message' => $response->getReasonPhrase(),
                'data' => json_decode($response->getBody()->getContents())
            ];
        };
        return $responses;
    }
}
